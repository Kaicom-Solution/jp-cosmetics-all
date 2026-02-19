<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\OrderActivity;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class OrderController extends Controller
{
    public function list()
    {
        $search_value  = request('search_value');
        $payment_status = request('payment_status');
        $status = request('status');
        $payment_method = request('payment_method');

        $orders = Order::with(['customer:id,name,email,phone'])
            ->when($search_value, function($query) use ($search_value) {
                $query->where('order_number', 'like', "%{$search_value}%")
                      ->orWhereHas('customer', function($internal_query) use ($search_value) {
                            $internal_query->where('name','like',"%{$search_value}%")
                                ->orWhere('email','like',"%{$search_value}%");
                });
            })
            ->when($payment_status, fn($query)=> $query->where('payment_status', $payment_status))
            ->when($status, fn($query)=> $query->where('status', $status))
            ->when($payment_method, fn($query)=> $query->where('payment_method', $payment_method))
            ->latest()
            ->paginate(10);

        return view('orders.list', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'customer:id,name,email,phone,status',
            'address:id,title,city,area,address,customer_id,is_default',
            'details:id,order_id,product_id,product_attribute_id,quantity,unit_price,sub_total,coupon_id,discount_amount,payable,created_at',
            'details.product:id,name,primary_image',
            'details.productAttribute:id,attribute_name,attribute_value',
            'activities:id,order_id,from_status,to_status,created_by,remarks,created_at'
        ])->findOrFail($id);
        

        return view('orders.show', compact('order'));
    }


    public function create()
    {
        $customers = Customer::with(['addresses' => function ($q) {
                $q->orderByDesc('is_default')->orderBy('id');
            }])
            ->orderBy('name')
            ->get(['id','name','email','phone','image','status']);

        $products = Product::with(['attributes' => function ($q) {
                $q->where('status', 1)
                  ->orderByDesc('is_default')
                  ->orderBy('id');
            }])
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id','name','slug','primary_image','product_type','status']);

        return view('orders.create', compact('customers', 'products'));
    }

    public function edit($id)
    {
        $order = Order::with(['customer','address'])->findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status'  => 'required|in:pending,confirm,dispatched,delivered,cancelled,returned,success',
            'remarks' => 'nullable|string|max:2000',
        ]);

        $order = Order::findOrFail($id);
        $from  = $order->status;
        $to    = $validated['status'];

        DB::transaction(function () use ($order, $from, $to, $validated) {

            $alreadyConfirmedBefore = OrderActivity::where('order_id', $order->id)
                ->where('to_status', Order::CONFIRM)
                ->exists();
    
            $order->update([
                'status'  => $to,
                'remarks' => $validated['remarks'] ?? $order->remarks,
            ]);
    
            if ($from !== Order::CONFIRM && $to === Order::CONFIRM && !$alreadyConfirmedBefore) {
                app(StockService::class)->reduceStockByOrderId($order->id);
            }
    
            OrderActivity::create([
                'order_id'    => $order->id,
                'from_status' => $from,
                'to_status'   => $to,
                'created_by'  => auth()->id(),
                'remarks'     => $validated['remarks'] ?? null,
            ]);
        });

        Toastr::success('Order updated.');
        return redirect()->route('order.show', $order->id);
    }

    public function print($id)
    {
        $order = Order::with([
            'customer',
            'address',
            'details.product',
            'details.productAttribute'
        ])->findOrFail($id);

        $html = view('orders.print', compact('order'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 12,
            'margin_bottom' => 12,
            'margin_left' => 12,
            'margin_right' => 12,
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output(
            'Order_'.$order->order_number.'.pdf',
            'I' // I = inline | D = download
        ))->header('Content-Type', 'application/pdf');
    }
}
