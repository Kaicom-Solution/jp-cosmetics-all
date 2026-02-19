<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductSearchResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $sort       = $request->query('sort');
        $categoryId = $request->query('category_id');
    
        $brandIdsStr = $request->query('brand_ids');
        $brandIds = [];
    
        if (!empty($brandIdsStr)) {
            $brandIds = array_filter(array_map('intval', explode(',', $brandIdsStr)));
        }

        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
    
        $products = Product::with(['category', 'brand', 'defaultAttribute'])
            ->addSelect([
                'default_price' => ProductAttribute::select('unit_price')
                    ->whereColumn('product_id', 'products.id')
                    ->where('status', 1)
                    ->where('is_default', 1)
                    ->whereNotNull('unit_price')
                    ->limit(1)
            ]);
    
        // if (!empty($categoryId)) {
        //     $products->where('category_id', (int) $categoryId);
        // }
        if (!empty($categoryId)) {
            $categoryIds = array_filter(array_map('intval', explode(',', $categoryId)));
            $products->whereIn('category_id', $categoryIds);
        }

        if (!empty($brandIds)) {
            $products->whereIn('brand_id', $brandIds);
        }

        if ($minPrice !== null || $maxPrice !== null) {
            $products->whereHas('attributes', function ($q) use ($minPrice, $maxPrice) {
                $q->where('status', 1)
                  ->whereNotNull('unit_price');
    
                if ($minPrice !== null && $maxPrice !== null) {
                    $q->whereBetween('unit_price', [(float) $minPrice, (float) $maxPrice]);
                } elseif ($minPrice !== null) {
                    $q->where('unit_price', '>=', (float) $minPrice);
                } else {
                    $q->where('unit_price', '<=', (float) $maxPrice);
                }
            });
        }

        switch ($sort) {
            case 'price_low':
                $products->orderBy('default_price', 'asc');
                break;
    
            case 'price_high':
                $products->orderBy('default_price', 'desc');
                break;
    
            case 'name_asc':
                $products->orderBy('name', 'asc');
                break;
    
            case 'name_desc':
                $products->orderBy('name', 'desc');
                break;
    
            case 'oldest':
                $products->orderBy('id', 'asc');
                break;
    
            case 'newest':
            default:
                $products->orderBy('id', 'desc');
                break;
        }
    
        $results = $products->paginate(20)->appends($request->query());
    
        // return ProductResource::collection($results)->response()->getData(true);
        return $this->responseWithSuccess(
                ProductResource::collection($results)->response()->getData(true),
                'Latest products fetched successfully',
                200
            );
    }

    public function show($slug)
    {
        $product = Product::with(['category','brand','attributes.attribute_images'])
                    ->where('slug', $slug)
                    ->first();

        if (!$product) {
            return $this->responseWithError('Product not found', 404);
        }

        return $this->responseWithSuccess(new ProductDetailResource($product));
    }


    public function getCategories($slug)
    {
        $product = Product::with('category.parent.parent')
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return $this->responseWithError('Product not found', 404);
        }

        $cat = $product->category;

        $categories = collect([
            $cat->parent?->parent,
            $cat->parent,
            $cat
        ])
        ->filter()
        ->map(function ($c) {
            return [
                'id'   => $c->id,
                'name' => $c->name,
                'slug' => $c->slug ?? null,
                'parent_id' => $c->parent_id,
            ];
        })
        ->values();

        return $this->responseWithSuccess(['product' => $product->name, 'productSlug'=> $product->slug, 'categories' => $categories], "Product categories fetched.");
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query || strlen($query) < 1) {
            return $this->responseWithSuccess([]);
        }

        $products = Product::with('defaultAttribute')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('slug', 'LIKE', "%{$query}%");
            })
            ->paginate(5);


        return $this->responseWithSuccess(ProductSearchResource::collection($products));
    }

    
    // public function relatedProducts($slug)
    // {
    //     $product = Product::with(['category', 'brand'])
    //         ->where('slug', $slug)
    //         ->first();

    //     if (!$product) {
    //         return $this->responseWithError('Product not found', 404);
    //     }

    //     $related = Product::with(['defaultAttribute'])
    //         ->where('id', '!=', $product->id)
    //         ->where(function ($q) use ($product) {
    //             $q->where('category_id', $product->category_id);

    //             if ($product->brand_id) {
    //                 $q->orWhere('brand_id', $product->brand_id);
    //             }
    //         })
    //         ->paginate(10);

    //     return $this->responseWithSuccess(
    //         ProductDetailResource::collection($related)
    //     );
    // }

    public function relatedProducts($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return $this->responseWithError('Product not found', 404);
        }

        // Category না থাকলে empty return করো
        if (!$product->category_id) {
            return $this->responseWithSuccess([]);
        }

        $related = Product::with(['category', 'brand', 'defaultAttribute'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->where('status', 'active') // active products only (যদি status থাকে)
            ->limit(8)
            ->get();

        // return $this->responseWithSuccess(
        //     ProductDetailResource::collection($related) // lightweight resource
        // );
        return $this->responseWithSuccess(
            $related->isEmpty() ? null : ProductDetailResource::collection($related)
        );
    }

    public function filterOptionsData()
    {
        $categories = Category::select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        $brands = Brand::select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        return $this->responseWithSuccess([
            'categories' => $categories,
            'brands'     => $brands,
        ]);
    }


}
