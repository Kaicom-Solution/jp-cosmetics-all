<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    // public function index()
    // {
    //     try {
    //         $brands = cache()->rememberForever('brands', function () {
    //             return Brand::select('id', 'name', 'slug', 'logo', 'description', 'status')
    //                 ->where('status', 1)
    //                 ->orderBy('id', 'desc')
    //                 ->get();
                    
    //         });

    //         return $this->responseWithSuccess($brands, 'Brands fetched successfully');

    //     } catch (Exception $e) {
    //         return $this->responseWithError('Unable to fetch brands', $e->getMessage());
    //     }
    // }

    public function show($slug)
    {
        try {
            $brand = Brand::where('slug', $slug)
                ->select('id', 'name', 'slug', 'logo', 'description', 'status')
                ->firstOrFail();

            return $this->responseWithSuccess($brand, 'Brand fetched successfully');

        } catch (Exception $e) {
            return $this->responseWithError('Brand not found', $e->getMessage());
        }
    }
}
