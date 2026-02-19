<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromotionPopup;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromotionPopupController extends Controller
{
    public function livePopup(): JsonResponse
    {
        try {
            $popup = PromotionPopup::select('id', 'title', 'description', 'image', 'button_text', 'button_url', 'created_at')
                ->where('status', 1)
                ->where('is_live', 1)
                ->first();

            if (!$popup) {
                return $this->responseWithError('No live promotion popup found', [], 404);
            }

            // Transform image path if needed
            $popup->image = $popup->image;

            return $this->responseWithSuccess($popup, 'Live promotion popup fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }
}
