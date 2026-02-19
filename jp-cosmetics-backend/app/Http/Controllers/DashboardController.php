<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAttribute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    // public function showDashboard()
    // {
    //     $todayStart = Carbon::today();
    //     $todayEnd   = Carbon::tomorrow();
    //     $monthStart = Carbon::now()->startOfMonth();
    //     $monthEnd   = Carbon::now()->endOfMonth();

    //     // -------------------------
    //     // KPI: Orders
    //     // -------------------------
    //     $totalOrders = Order::count();

    //     $todayOrders = Order::whereBetween('created_at', [$todayStart, $todayEnd])->count();

    //     $pendingOrders = Order::where('status', Order::PENDING)->count();

    //     // if you have constants like DELIVERED/CANCELLED, use them
    //     $deliveredOrders = defined(Order::class.'::DELIVERED')
    //         ? Order::where('status', Order::DELIVERED)->count()
    //         : Order::where('status', 'delivered')->count(); // fallback

    //     // -------------------------
    //     // KPI: Sales
    //     // -------------------------
    //     $todaySales = Order::whereBetween('created_at', [$todayStart, $todayEnd])
    //         ->whereIn('payment_status', ['success', 'processing', 'pending']) // adjust if needed
    //         ->sum('payable_total');

    //     $monthSales = Order::whereBetween('created_at', [$monthStart, $monthEnd])
    //         ->whereIn('payment_status', ['success', 'processing', 'pending'])
    //         ->sum('payable_total');

    //     // -------------------------
    //     // Customers / Products / Coupons
    //     // -------------------------
    //     $totalCustomers = Customer::count();

    //     // If product has status column, use it; otherwise count all.
    //     $activeProducts = Product::query()
    //         ->when(Schema::hasColumn('products', 'status'), fn($q) => $q->where('status', 1))
    //         ->count();

    //     // Inventory (attributes stock)
    //     $lowStockThreshold = 5;

    //     $lowStockCount = ProductAttribute::where('status', 1)
    //         ->where('stock', '<=', $lowStockThreshold)
    //         ->count();

    //     $outOfStockCount = ProductAttribute::where('status', 1)
    //         ->where('stock', '=', 0)
    //         ->count();

    //     $activeCoupons = Coupon::where('status', 'active')->count();

    //     // -------------------------
    //     // Recent Orders
    //     // -------------------------
    //     $recentOrders = Order::with('customer')
    //         ->latest()
    //         ->take(10)
    //         ->get();

    //     // -------------------------
    //     // Low Stock List (top 8)
    //     // -------------------------
    //     $lowStockItems = ProductAttribute::with('product:id,name,slug,primary_image')
    //         ->where('status', 1)
    //         ->where('stock', '<=', $lowStockThreshold)
    //         ->orderBy('stock', 'asc')
    //         ->take(8)
    //         ->get();

    //     // -------------------------
    //     // Simple Chart Data: last 7 days sales + orders
    //     // (for Chart.js later)
    //     // -------------------------
    //     $last7Days = collect(range(0, 6))->map(function ($i) {
    //         return Carbon::today()->subDays(6 - $i);
    //     });

    //     $salesByDay = [];
    //     $ordersByDay = [];

    //     foreach ($last7Days as $day) {
    //         $start = $day->copy()->startOfDay();
    //         $end   = $day->copy()->endOfDay();

    //         $salesByDay[] = (float) Order::whereBetween('created_at', [$start, $end])
    //             ->whereIn('payment_status', ['success', 'processing', 'pending'])
    //             ->sum('payable_total');

    //         $ordersByDay[] = (int) Order::whereBetween('created_at', [$start, $end])->count();
    //     }

    //     $chartLabels = $last7Days->map(fn($d) => $d->format('d M'))->toArray();

    //     return view('dashboard.dashboard', compact(
    //         'totalOrders',
    //         'todayOrders',
    //         'pendingOrders',
    //         'deliveredOrders',
    //         'todaySales',
    //         'monthSales',
    //         'totalCustomers',
    //         'activeProducts',
    //         'lowStockCount',
    //         'outOfStockCount',
    //         'activeCoupons',
    //         'recentOrders',
    //         'lowStockItems',
    //         'chartLabels',
    //         'salesByDay',
    //         'ordersByDay',
    //         'lowStockThreshold'
    //     ));
    // }
    
    // public function showProfile()
    // {
    //     $superAdmin = Auth::user();
    //     return view('users.profile', compact('superAdmin'));
    // }










    public function showDashboard(Request $request)
    {
        // ── Date Range ───────────────────────────────────
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfMonth();

        // ── KPI: Orders (date-filtered) ──────────────────
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        $pendingOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->count();

        $deliveredOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'delivered')
            ->count();

        // ── KPI: Sales (date-filtered, payment success only) ─
        $totalSales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'success')
            ->whereNotIn('status', ['pending', 'cancelled', 'returned'])
            ->sum('payable_total');

        // ── Static KPIs (not date-filtered) ─────────────
        $totalCustomers = Customer::count();

        $activeProducts = Product::query()
            ->when(Schema::hasColumn('products', 'status'), fn($q) => $q->where('status', 'active'))
            ->count();

        $lowStockThreshold = 5;

        $lowStockCount = ProductAttribute::where('status', 1)
            ->where('stock', '<=', $lowStockThreshold)
            ->count();

        $outOfStockCount = ProductAttribute::where('status', 1)
            ->where('stock', '=', 0)
            ->count();

        $activeCoupons = Coupon::where('status', 'active')->count();

        // ── Chart: daily breakdown within selected range ──
        // Generate day-by-day labels within range (max 60 points, otherwise group by week/month)
        $diffDays = $startDate->diffInDays($endDate);

        if ($diffDays <= 31) {
            // Day-by-day
            $days = collect();
            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $days->push($cursor->copy());
                $cursor->addDay();
            }
            $chartLabels   = $days->map(fn($d) => $d->format('d M'))->toArray();
            $salesByDay    = [];
            $ordersByDay   = [];
            foreach ($days as $day) {
                $s = $day->copy()->startOfDay();
                $e = $day->copy()->endOfDay();

                $salesByDay[] = (float) Order::whereBetween('created_at', [$s, $e])
                    ->where('payment_status', 'success')
                    ->whereNotIn('status', ['pending', 'cancelled', 'returned'])
                    ->sum('payable_total');

                $ordersByDay[] = (int) Order::whereBetween('created_at', [$s, $e])->count();
            }
        } else {
            // Week-by-week grouping for longer ranges
            $weeks = collect();
            $cursor = $startDate->copy()->startOfWeek();
            while ($cursor->lte($endDate)) {
                $weeks->push($cursor->copy());
                $cursor->addWeek();
            }
            $chartLabels = $weeks->map(fn($d) => 'W' . $d->weekOfYear . ' ' . $d->format('M'))->toArray();
            $salesByDay  = [];
            $ordersByDay = [];
            foreach ($weeks as $weekStart) {
                $s = $weekStart->copy();
                $e = $weekStart->copy()->endOfWeek();

                $salesByDay[] = (float) Order::whereBetween('created_at', [$s, $e])
                    ->where('payment_status', 'success')
                    ->whereNotIn('status', ['pending', 'cancelled', 'returned'])
                    ->sum('payable_total');

                $ordersByDay[] = (int) Order::whereBetween('created_at', [$s, $e])->count();
            }
        }

        return view('dashboard.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'totalSales',
            'totalCustomers',
            'activeProducts',
            'lowStockCount',
            'outOfStockCount',
            'activeCoupons',
            'chartLabels',
            'salesByDay',
            'ordersByDay',
            'lowStockThreshold',
            'startDate',
            'endDate'
        ));
    }

    // ── JSON endpoint for AJAX date-range reload ─────────
    public function dashboardData(Request $request)
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfMonth();

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        $pendingOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')->count();

        $deliveredOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'delivered')->count();

        $totalSales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'success')
            ->whereNotIn('status', ['pending', 'cancelled', 'returned'])
            ->sum('payable_total');

        $diffDays = $startDate->diffInDays($endDate);

        if ($diffDays <= 31) {
            $days = collect();
            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $days->push($cursor->copy());
                $cursor->addDay();
            }
            $chartLabels = $days->map(fn($d) => $d->format('d M'))->toArray();
            $salesByDay  = [];
            $ordersByDay = [];
            foreach ($days as $day) {
                $s = $day->copy()->startOfDay();
                $e = $day->copy()->endOfDay();
                $salesByDay[]  = (float) Order::whereBetween('created_at', [$s, $e])
                    ->where('payment_status', 'success')
                    ->whereNotIn('status', ['pending', 'cancelled', 'returned'])
                    ->sum('payable_total');
                $ordersByDay[] = (int) Order::whereBetween('created_at', [$s, $e])->count();
            }
        } else {
            $weeks = collect();
            $cursor = $startDate->copy()->startOfWeek();
            while ($cursor->lte($endDate)) {
                $weeks->push($cursor->copy());
                $cursor->addWeek();
            }
            $chartLabels = $weeks->map(fn($d) => 'W' . $d->weekOfYear . ' ' . $d->format('M'))->toArray();
            $salesByDay  = [];
            $ordersByDay = [];
            foreach ($weeks as $weekStart) {
                $s = $weekStart->copy();
                $e = $weekStart->copy()->endOfWeek();
                $salesByDay[]  = (float) Order::whereBetween('created_at', [$s, $e])
                    ->where('payment_status', 'success')
                    ->whereNotIn('status', ['pending', 'cancelled', 'returned'])
                    ->sum('payable_total');
                $ordersByDay[] = (int) Order::whereBetween('created_at', [$s, $e])->count();
            }
        }

        return response()->json([
            'kpi' => [
                'totalOrders'     => $totalOrders,
                'pendingOrders'   => $pendingOrders,
                'deliveredOrders' => $deliveredOrders,
                'totalSales'      => $totalSales,
            ],
            'chart' => [
                'labels' => $chartLabels,
                'sales'  => $salesByDay,
                'orders' => $ordersByDay,
            ],
        ]);
    }

    public function showProfile()
    {
        $superAdmin = Auth::user();
        return view('users.profile', compact('superAdmin'));
    }
}
