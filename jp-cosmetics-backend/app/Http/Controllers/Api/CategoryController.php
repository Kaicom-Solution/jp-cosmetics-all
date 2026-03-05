<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\RequestProduct;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index(): JsonResponse
    {
        try {
            $categories = Category::select('id', 'name', 'parent_id', 'slug', 'sequence', 'image', 'is_popular', 'description')
                ->whereNull('parent_id')
                ->where('status', 1)
                ->orderBy('sequence', 'desc')
                ->get();

            return $this->responseWithSuccess($categories, 'Category list fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    // public function show($slug): JsonResponse
    // {
    //     try {
    //         $category = Category::select('id', 'name', 'parent_id', 'slug', 'sequence', 'image', 'is_popular', 'description')
    //             ->where('slug', $slug)
    //             ->where('status', 1)
    //             ->first();

    //         if (!$category) {
    //             return $this->responseWithError('Category not found', [], 404);
    //         }

    //         // find subcategory is there or not
    //         $subCategories = Category::select('id', 'name', 'parent_id', 'slug', 'sequence', 'image', 'is_popular', 'description')
    //             ->where('parent_id', $category->id)
    //             ->where('status', 1)
    //             ->orderBy('sequence', 'desc')
    //             ->get();

    //         if ($subCategories->isNotEmpty()) {
    //             $category->type        = 'subcategories';
    //             $category->children    = $subCategories;
    //         } else {
    //             $products = Product::select('id', 'name', 'slug', 'thumbnail', 'price', 'discount_price')
    //                 ->where('category_id', $category->id)
    //                 ->where('status', 1)
    //                 ->orderBy('id', 'desc')
    //                 ->paginate(15);

    //             $category->type     = 'products';
    //             $category->children = $products;
    //         }

    //         return $this->responseWithSuccess($category, 'Category fetched successfully', 200);

    //     } catch (Exception $e) {
    //         return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
    //     }
    // }

    public function tree($slug): JsonResponse
    {
        try {
            $category = Category::select('id', 'name', 'parent_id', 'slug', 'sequence', 'image', 'is_popular', 'description')
                ->where('slug', $slug)
                ->where('status', 1)
                ->first();

            if (!$category) {
                return $this->responseWithError('Category not found', [], 404);
            }

            $category->children = $this->buildTree($category->id);

            return $this->responseWithSuccess($category, 'Category tree fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    private function buildTree(int $parentId)
    {
        $children = Category::select('id', 'name', 'parent_id', 'slug', 'sequence', 'image', 'is_popular', 'description')
            ->where('parent_id', $parentId)
            ->where('status', 1)
            ->orderBy('sequence', 'desc')
            ->get();

        foreach ($children as $child) {
            $child->children = $this->buildTree($child->id); // recursive
        }

        return $children;
    }

    public function products($slug): JsonResponse
    {
        try {
            $products = Product::with(['category', 'brand', 'defaultAttribute'])
                ->addSelect([
                    'default_price' => ProductAttribute::select('unit_price')
                        ->whereColumn('product_id', 'products.id')
                        ->where('status', 1)
                        ->where('is_default', 1)
                        ->whereNotNull('unit_price')
                        ->limit(1)
                ])
                ->whereHas('category', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                })
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->paginate(15);

            $resource = ProductResource::collection($products)->response()->getData(true);

            return $this->responseWithSuccess($resource, 'Products fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    public function popularCategories(): JsonResponse
    {
        try {

            $categories = Category::select('id', 'name', 'parent_id', 'slug', 'sequence', 'image', 'is_popular', 'description') 
                ->where('status', 1)
                ->where('is_popular', 1)
                ->orderBy('sequence', 'desc')
                ->take(2)
                ->get();

            $categories->transform(function ($category) {
                $category->image = $category->image;
                return $category;
            });

            return $this->responseWithSuccess($categories, 'Popular categories fetched successfully', 200);

        } catch (Exception $e) {

            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }



    public function popularProducts(): JsonResponse
    {
        try {

            // Step Get top product IDs ordered by count
            $topProducts = OrderDetail::select('product_id', DB::raw('COUNT(product_id) as total_orders'))
                ->groupBy('product_id')
                ->orderByDesc('total_orders')
                ->limit(12)
                ->pluck('product_id');

            // Step Fetch product details
            $products = Product::with([
                    'category:id,name,slug',
                    'brand:id,name,slug'
                ])
                ->select('id', 'name', 'slug', 'product_type', 'status', 'primary_image', 'category_id', 'brand_id', 'created_at')
                ->whereIn('id', $topProducts)
                ->where('status', 'active')
                ->get();

            // Step Convert image paths
            // $products->transform(function ($item) {
            //     $item->primary_image = $item->primary_image ? asset($item->primary_image) : null;
            //     return $item;
            // });

            return $this->responseWithSuccess(
                ProductResource::collection($products),
                'Popular products fetched successfully',
                200
            );

        } catch (Exception $e) {
            return $this->responseWithError(
                'Unable to fetch popular products',
                [$e->getMessage()],
                500
            );
        }
    }

    public function trendingProducts(): JsonResponse
    {
        try {

            $products = Product::with([
                    'category:id,name,slug',
                    'brand:id,name,slug'
                ])
                ->select('id', 'name', 'slug', 'product_type', 'status', 'primary_image', 'category_id', 'brand_id', 'created_at')
                ->where('status', 'active')
                ->orderByDesc('created_at')
                ->limit(12)
                ->get();

            // Convert image paths to full URL
            // $products->transform(function ($item) {
            //     $item->primary_image = $item->primary_image ? asset($item->primary_image) : null;
            //     return $item;
            // });

            return $this->responseWithSuccess(
                ProductResource::collection($products),
                'Latest products fetched successfully',
                200
            );

        } catch (Exception $e) {
            return $this->responseWithError(
                'Unable to fetch latest products',
                [$e->getMessage()],
                500
            );
        }
    }

    public function requestProductStore(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name'    => 'required|string|max:255',
                'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'details' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(
                    'Validation failed',
                    $validator->errors(),
                    422
                );
            }

            $data = [
                'customer_id' => auth('customer')->id(),
                'name'        => $request->name,
                'details'     => $request->details,
            ];

            /* Image upload (same as category) */
            if ($request->hasFile('image')) {

                $dest = public_path('uploads/product_request_images');

                if (!file_exists($dest)) {
                    mkdir($dest, 0777, true);
                }

                $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
                $request->file('image')->move($dest, $imageName);

                $data['image'] = 'uploads/product_request_images/' . $imageName;
            }

            RequestProduct::create($data);

            return $this->responseWithSuccess(
                null,
                'Product request submitted successfully',
                200
            );

        } catch (Exception $e) {

            return $this->responseWithError(
                'Unable to submit product request',
                [$e->getMessage()],
                500
            );
        }
       
    }



}
