<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    private function parseDateRange(Request $request): array
    {
        $start = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $end = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfMonth();

        return [$start, $end];
    }

    // ─────────────────────────────────────────────
    //  1. Customer-wise Orders
    // ─────────────────────────────────────────────
    public function customerOrders()
    {
        return view('analytics.customer_orders');
    }
    public function customerOrdersData(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);

        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status', 'success')
            ->whereNotIn('orders.status', ['pending', 'cancelled', 'returned'])
            ->select(
                'customers.id as customer_id',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'orders.id as order_id',
                'orders.order_number',
                'orders.status',
                'orders.payment_status',
                'orders.payment_method',
                'orders.payable_total',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc');

        // DataTables server-side
        if ($request->ajax()) {
            $totalRecords = $query->count();
            $filteredRecords = $totalRecords;

            // Search
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('customers.name', 'like', "%$search%")
                      ->orWhere('customers.email', 'like', "%$search%")
                      ->orWhere('customers.phone', 'like', "%$search%")
                      ->orWhere('orders.order_number', 'like', "%$search%");
                });
                $filteredRecords = $query->count();
            }

            // Order
            $orderCol   = $request->input('order.0.column', 0);
            $orderDir   = $request->input('order.0.dir', 'desc');
            $columns    = ['orders.created_at', 'customers.name', 'orders.order_number', 'orders.status', 'orders.payment_status', 'orders.payable_total'];
            if (isset($columns[$orderCol])) {
                $query->orderBy($columns[$orderCol], $orderDir);
            }

            // Paginate
            $start  = (int) $request->input('start', 0);
            $length = (int) $request->input('length', 10);
            $data   = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw'            => intval($request->input('draw')),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data'            => $data,
            ]);
        }

        return response()->json(['data' => $query->get()]);
    }

    public function customerOrdersExport(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);

        $rows = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status', 'success')
            ->whereNotIn('orders.status', ['pending', 'cancelled', 'returned'])
            ->select(
                'customers.id as customer_id',
                'customers.name as customer_name',
                'customers.email',
                'customers.phone',
                'orders.order_number',
                'orders.status',
                'orders.payment_status',
                'orders.payment_method',
                'orders.payable_total',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $filename = 'Customer_Order_Report_of_' . $start->format('Y-m-d') . '_to_' . $end->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Customer ID', 'Name', 'Email', 'Phone', 'Order Number', 'Order Status', 'Payment Status', 'Payment Method', 'Payable Total', 'Date']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->customer_id,
                    $row->customer_name,
                    $row->email,
                    $row->phone,
                    $row->order_number,
                    $row->status,
                    $row->payment_status,
                    $row->payment_method,
                    $row->payable_total,
                    $row->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    // ─────────────────────────────────────────────
    //  2. Total Sales Report
    // ─────────────────────────────────────────────
    public function totalSales()
    {
        return view('analytics.total_sales');
    }

    public function totalSalesData(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);

        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status', 'success')
            ->whereNotIn('orders.status', ['pending', 'cancelled', 'returned'])
            ->select(
                'orders.id as order_id',
                'orders.order_number',
                'customers.name as customer_name',
                'customers.phone',
                'orders.sub_total_amount',
                'orders.delivery_charge',
                'orders.discount_amount',
                'orders.discount_from_coupon',
                'orders.payable_total',
                'orders.payment_method',
                'orders.payment_status',
                'orders.status',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc');

        if ($request->ajax()) {
            $totalRecords = $query->count();
            $filteredRecords = $totalRecords;

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('orders.order_number', 'like', "%$search%")
                      ->orWhere('customers.name', 'like', "%$search%")
                      ->orWhere('customers.phone', 'like', "%$search%");
                });
                $filteredRecords = $query->count();
            }

            $orderCol  = $request->input('order.0.column', 0);
            $orderDir  = $request->input('order.0.dir', 'desc');
            $columns   = ['orders.created_at', 'orders.order_number', 'customers.name', 'orders.payable_total', 'orders.status'];
            if (isset($columns[$orderCol])) {
                $query->orderBy($columns[$orderCol], $orderDir);
            }

            $startIdx = (int) $request->input('start', 0);
            $length   = (int) $request->input('length', 10);
            $data     = $query->skip($startIdx)->take($length)->get();

            // Summary totals for the full filtered set (without pagination)
            $summary = DB::table('orders')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->whereBetween('orders.created_at', [$start, $end])
                ->where('orders.payment_status', 'success')
                ->whereNotIn('orders.status', ['pending', 'cancelled', 'returned'])
                ->selectRaw('
                    COUNT(*) as total_orders,
                    SUM(orders.sub_total_amount) as total_subtotal,
                    SUM(orders.delivery_charge) as total_delivery,
                    SUM(COALESCE(orders.discount_amount,0) + COALESCE(orders.discount_from_coupon,0)) as total_discount,
                    SUM(orders.payable_total) as total_payable
                ')->first();

            return response()->json([
                'draw'            => intval($request->input('draw')),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data'            => $data,
                'summary'         => $summary,
            ]);
        }

        return response()->json(['data' => $query->get()]);
    }

    public function totalSalesExport(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);

        $rows = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status', 'success')
            ->whereNotIn('orders.status', ['pending', 'cancelled', 'returned'])
            ->select(
                'orders.order_number',
                'customers.name as customer_name',
                'customers.phone',
                'orders.sub_total_amount',
                'orders.delivery_charge',
                'orders.discount_amount',
                'orders.discount_from_coupon',
                'orders.payable_total',
                'orders.payment_method',
                'orders.payment_status',
                'orders.status',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $filename = 'Total_Sales_Report_of_' . $start->format('Y-m-d') . '_to_' . $end->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order Number', 'Customer', 'Phone', 'Sub Total', 'Delivery Charge', 'Discount', 'Coupon Discount', 'Payable Total', 'Payment Method', 'Payment Status', 'Order Status', 'Date']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->order_number,
                    $row->customer_name,
                    $row->phone,
                    $row->sub_total_amount,
                    $row->delivery_charge,
                    $row->discount_amount ?? 0,
                    $row->discount_from_coupon ?? 0,
                    $row->payable_total,
                    $row->payment_method,
                    $row->payment_status,
                    $row->status,
                    $row->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    // ─────────────────────────────────────────────
    //  3. Total Sales by Products
    // ─────────────────────────────────────────────
    public function productSales()
    {
        return view('analytics.product_sales');
    }

    public function productSalesData(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);

        $query = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('product_attributes', 'order_details.product_attribute_id', '=', 'product_attributes.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status', 'success')
            ->whereNotIn('orders.status', ['pending', 'cancelled', 'returned'])
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'product_attributes.attribute_name',
                'product_attributes.attribute_value',
                DB::raw('SUM(order_details.quantity) as total_qty'),
                DB::raw('AVG(order_details.unit_price) as avg_unit_price'),
                DB::raw('SUM(order_details.sub_total) as total_sub_total'),
                DB::raw('SUM(COALESCE(order_details.discount_amount,0)) as total_discount'),
                DB::raw('SUM(order_details.payable) as total_payable'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->groupBy(
                'products.id',
                'products.name',
                'product_attributes.attribute_name',
                'product_attributes.attribute_value'
            )
            ->orderByDesc('total_qty');

        if ($request->ajax()) {
            $totalRecords    = DB::table(DB::raw("({$query->toSql()}) as sub"))->mergeBindings($query)->count();
            $filteredRecords = $totalRecords;

            if ($search = $request->input('search.value')) {
                $query->having('products.name', 'like', "%$search%")
                      ->orHaving('product_attributes.attribute_value', 'like', "%$search%");
                $filteredRecords = DB::table(DB::raw("({$query->toSql()}) as sub"))->mergeBindings($query)->count();
            }

            $orderCol = $request->input('order.0.column', 0);
            $orderDir = $request->input('order.0.dir', 'desc');
            $cols     = ['products.name', 'product_attributes.attribute_name', 'product_attributes.attribute_value', 'total_qty', 'total_payable', 'total_orders'];
            if (isset($cols[$orderCol])) {
                $query->reorder($cols[$orderCol], $orderDir);
            }

            $startIdx = (int) $request->input('start', 0);
            $length   = (int) $request->input('length', 10);
            $data     = $query->skip($startIdx)->take($length)->get();

            return response()->json([
                'draw'            => intval($request->input('draw')),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data'            => $data,
            ]);
        }

        return response()->json(['data' => $query->get()]);
    }

    public function productSalesExport(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);

        $rows = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('product_attributes', 'order_details.product_attribute_id', '=', 'product_attributes.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status', 'success')
            ->whereNotIn('orders.status', ['pending', 'cancelled', 'returned'])
            ->select(
                'products.name as product_name',
                'product_attributes.attribute_name',
                'product_attributes.attribute_value',
                DB::raw('SUM(order_details.quantity) as total_qty'),
                DB::raw('AVG(order_details.unit_price) as avg_unit_price'),
                DB::raw('SUM(order_details.sub_total) as total_sub_total'),
                DB::raw('SUM(COALESCE(order_details.discount_amount,0)) as total_discount'),
                DB::raw('SUM(order_details.payable) as total_payable'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->groupBy('products.id', 'products.name', 'product_attributes.attribute_name', 'product_attributes.attribute_value')
            ->orderByDesc('total_qty')
            ->get();

        $filename = 'Product_Sales_Report_of_' . $start->format('Y-m-d') . '_to_' . $end->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Product Name', 'Attribute Name', 'Attribute Value', 'Total Qty Sold', 'Avg Unit Price', 'Total Sub Total', 'Total Discount', 'Total Payable', 'Total Orders']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->product_name,
                    $row->attribute_name,
                    $row->attribute_value,
                    $row->total_qty,
                    number_format($row->avg_unit_price, 2),
                    $row->total_sub_total,
                    $row->total_discount,
                    $row->total_payable,
                    $row->total_orders,
                ]);
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
