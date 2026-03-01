<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = OrderDetail::with(['order.customer', 'product'])
            ->whereNotNull('rating')
            ->latest()
            ->paginate(20);

        return view('reviews.index', compact('reviews'));
    }

    public function toggleApprove(OrderDetail $orderDetail)
    {
        $orderDetail->update([
            'is_approved_review' => !$orderDetail->is_approved_review,
        ]);

        return back()->with('success', 'Review status updated.');
    }

    public function destroy(OrderDetail $orderDetail)
    {
        $orderDetail->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}
