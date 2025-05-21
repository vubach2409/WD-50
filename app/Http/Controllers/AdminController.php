<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Validate date inputs
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            // End date cannot be in the future, and must be after or equal to start_date if start_date is provided
            'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:' . Carbon::today()->toDateString(),
        ]);

        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        $queryStartDate = $startDateInput ? Carbon::parse($startDateInput)->startOfDay() : null;
        $queryEndDate = $endDateInput ? Carbon::parse($endDateInput)->endOfDay() : null;

        // Dữ liệu cho các card thống kê
        $totalProducts = Product::count();
        
        $totalOrdersQuery = Orders::query();
        if ($queryStartDate && $queryEndDate) {
            $totalOrdersQuery->whereBetween('created_at', [$queryStartDate, $queryEndDate]);
        }
        $totalOrders = $totalOrdersQuery->count();

        $totalCustomers = User::where('role', '!=', 'admin')->count();

        // Doanh thu tháng này (chỉ tính đơn hàng đã hoàn thành)
        $totalRevenueThisMonth = Orders::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Sản phẩm mới trong 30 ngày gần nhất
        $newProducts = Product::where('created_at', '>=', now()->subDays(30))->count();

        // --- Dữ liệu cho Biểu đồ Doanh thu ---
        $revenueChartLabels = [];
        $revenueChartData = [];

        $revenueQueryBase = Orders::where('status', 'completed');

        if ($queryStartDate && $queryEndDate) {
            $dailyRevenue = $revenueQueryBase
                ->whereBetween('created_at', [$queryStartDate, $queryEndDate])
                ->select(
                    DB::raw("DATE(created_at) as order_date"),
                    DB::raw("SUM(total) as daily_total")
                )
                ->groupBy('order_date')
                ->orderBy('order_date', 'ASC')
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::parse($item->order_date)->format('Y-m-d');
                });

            $currentDate = $queryStartDate->clone();
            while ($currentDate <= $queryEndDate) {
                $dateKey = $currentDate->format('Y-m-d');
                $revenueChartLabels[] = $currentDate->format('d/m/Y');
                $revenueChartData[] = $dailyRevenue->get($dateKey)->daily_total ?? 0;
                $currentDate->addDay();
            }
        } else {
            // Default: Last 7 days
            $startOf7Days = Carbon::today()->subDays(6)->startOfDay();
            $endOf7Days = Carbon::today()->endOfDay();

            $dailyRevenue = $revenueQueryBase
                ->whereBetween('created_at', [$startOf7Days, $endOf7Days])
                ->select(
                    DB::raw("DATE(created_at) as order_date"),
                    DB::raw("SUM(total) as daily_total")
                )
                ->groupBy('order_date')
                ->orderBy('order_date', 'ASC')
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::parse($item->order_date)->format('Y-m-d');
                });
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dateKey = $date->format('Y-m-d');
                $revenueChartLabels[] = $date->format('d/m');
                $revenueChartData[] = $dailyRevenue->get($dateKey)->daily_total ?? 0;
            }
        }


        // --- Dữ liệu cho Biểu đồ Tỷ lệ Trạng thái Đơn hàng ---
        $orderStatusQuery = Orders::query();
        if ($queryStartDate && $queryEndDate) {
            $orderStatusQuery->whereBetween('created_at', [$queryStartDate, $queryEndDate]);
        }
        
        $orderStatusCounts = $orderStatusQuery->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')->all();

        $statusTranslations = [
            'pending' => 'Chờ xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Thất bại',
            // Thêm các trạng thái khác nếu có
        ];

        $orderStatusLabels = [];
        $orderStatusData = [];
        // Đảm bảo thứ tự và đầy đủ các trạng thái
        foreach ($statusTranslations as $statusKey => $displayName) {
            $orderStatusLabels[] = $displayName;
            $orderStatusData[] = $orderStatusCounts[$statusKey] ?? 0;
        }
        // Xử lý các trạng thái không có trong $statusTranslations (nếu có)
        foreach ($orderStatusCounts as $status => $count) {
            if (!array_key_exists($status, $statusTranslations)) {
                $orderStatusLabels[] = ucfirst($status);
                $orderStatusData[] = $count;
            }
        }


        // --- Dữ liệu cho Sản phẩm bán chạy nhất (Top 5) ---
        $bestSellingQuery = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed');

        if ($queryStartDate && $queryEndDate) {
            $bestSellingQuery->whereBetween('orders.created_at', [$queryStartDate, $queryEndDate]);
        }

        $bestSellingProductsRaw = $bestSellingQuery
            ->select('products.name as product_name', DB::raw('SUM(order_details.quantity) as total_quantity_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity_sold')
            ->limit(5)
            ->get();
        $bestSellingProductLabels = [];
        $bestSellingProductData = [];
        foreach ($bestSellingProductsRaw as $product) {
            $bestSellingProductLabels[] = $product->product_name;
            $bestSellingProductData[] = $product->total_quantity_sold;
        }

        // --- Dữ liệu cho Sản phẩm bán ế nhất (Top 5) ---
        $salesSubquery = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed');
            
        if ($queryStartDate && $queryEndDate) {
            $salesSubquery->whereBetween('orders.created_at', [$queryStartDate, $queryEndDate]);
        }
        
        $salesSubquery->select('order_details.product_id', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('order_details.product_id');


        $leastSellingProductsRaw = DB::table('products')
            ->leftJoinSub($salesSubquery, 'sales', function ($join) {
                $join->on('products.id', '=', 'sales.product_id');
            })
            ->select('products.name as product_name', DB::raw('COALESCE(sales.total_sold, 0) as total_quantity_sold'))
            ->orderBy('total_quantity_sold', 'asc')
            ->orderBy('products.name', 'asc')
            ->limit(5)
            ->get();

        $leastSellingProductLabels = [];
        $leastSellingProductData = [];
        foreach ($leastSellingProductsRaw as $product) {
            $leastSellingProductLabels[] = $product->product_name;
            $leastSellingProductData[] = $product->total_quantity_sold;
        }

        // Truyền dữ liệu vào view
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders', // Đã được lọc
            'totalCustomers',
            'totalRevenueThisMonth',
            'newProducts',
            'revenueChartLabels', 
            'revenueChartData',   
            'orderStatusLabels',
            'orderStatusData',
            'bestSellingProductLabels',
            'bestSellingProductData',
            'leastSellingProductLabels',
            'leastSellingProductData',
            'startDateInput',
            'endDateInput'
        ));
    }
}