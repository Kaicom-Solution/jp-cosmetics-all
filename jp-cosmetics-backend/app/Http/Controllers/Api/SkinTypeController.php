<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkinType;
use Exception;
use Illuminate\Http\Request;

class SkinTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $skinTypes = SkinType::select('id', 'name', 'slug', 'logo', 'description', 'status')
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return $this->responseWithSuccess($skinTypes, 'Skin types fetched successfully');

        } catch (Exception $e) {
            return $this->responseWithError('Unable to fetch skin types', $e->getMessage());
        }
    }

    public function show($slug)
    {
        try {
            $skinType = SkinType::where('slug', $slug)
                ->select('id', 'name', 'slug', 'logo', 'description', 'status')
                ->firstOrFail();

            return $this->responseWithSuccess($skinType, 'Skin type fetched successfully');

        } catch (Exception $e) {
            return $this->responseWithError('Skin type not found', $e->getMessage());
        }
    }
}   
