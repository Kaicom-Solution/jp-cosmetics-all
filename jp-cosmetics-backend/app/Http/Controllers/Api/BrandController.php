<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductAttribute;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $brands = Brand::select('id', 'name', 'slug', 'logo', 'description', 'status')
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return $this->responseWithSuccess($brands, 'Brands fetched successfully');

        } catch (Exception $e) {
            return $this->responseWithError('Unable to fetch brands', $e->getMessage());
        }
    }

    public function show($slug): JsonResponse
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
                ->wherehas('brand', function ($query) use ($slug) {
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
}
