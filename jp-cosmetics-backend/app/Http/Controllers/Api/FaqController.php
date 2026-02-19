<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $faqs = Faq::select('id', 'question', 'answer', 'order')
                ->where('status', 1)
                ->orderBy('order', 'asc')
                ->get();

            return $this->responseWithSuccess($faqs, 'FAQs fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }
}
