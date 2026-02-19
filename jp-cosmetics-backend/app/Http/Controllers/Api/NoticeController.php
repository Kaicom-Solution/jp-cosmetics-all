<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function liveNotice(): JsonResponse
    {
        try {
            $notice = Notice::select('id', 'title', 'description', 'created_at')
                ->where('status', 1)
                ->where('is_live', 1)
                ->first();

            if (!$notice) {
                return $this->responseWithError('No live notice found', [], 404);
            }

            return $this->responseWithSuccess($notice, 'Live notice fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }

}
