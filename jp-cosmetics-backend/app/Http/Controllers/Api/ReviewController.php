<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $details = $order->orderDetails()->with('product')->get()
            ->map(function ($detail) {
                return [
                    'order_detail_id'     => $detail->id,
                    'product_id'          => $detail->product_id,
                    'product_name'        => $detail->product->name ?? null,
                    'rating'              => $detail->rating,
                    'review'              => $detail->review,
                    'is_approved_review'  => $detail->is_approved_review,
                ];
            });

        return response()->json([
            'order_status' => $order->status,
            'can_review'   => $order->status === 'delivered',
            'reviews'      => $details,
        ]);
    }

    public function store(Request $request, Order $order, OrderDetail $orderDetail)
    {
        // Authorization check
        if ($order->customer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Order delivered check
        if ($order->status !== 'delivered') {
            return response()->json([
                'message' => 'Review can only be submitted after order is delivered.'
            ], 422);
        }

        // orderDetail from this order check
        if ($orderDetail->order_id !== $order->id) {
            return response()->json(['message' => 'Invalid product for this order.'], 422);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);

        $orderDetail->update([
            'rating'             => $request->rating,
            'review'             => $request->review,
            'is_approved_review' => 0, 
        ]);

        return response()->json([
            'message' => 'Review submitted successfully. Waiting for approval.',
            'data'    => $orderDetail->only(['id', 'rating', 'review', 'is_approved_review']),
        ]);
    }
}
