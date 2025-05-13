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
        $totalOrders = Orders::count();
        $totalCustomers = User::where('role', '!=', 'admin')->count();

        // Doanh thu tháng này (chỉ tính đơn hàng đã hoàn thành)
        // Giả sử cột tổng tiền của đơn hàng là 'total' và trạng thái hoàn thành là 'completed'
        $totalRevenueThisMonth = Orders::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Sản phẩm mới trong 30 ngày gần nhất
        $newProducts = Product::where('created_at', '>=', now()->subDays(30))->count();

        // --- Dữ liệu cho Biểu đồ Doanh thu 7 ngày gần nhất ---
        $revenueLast7DaysLabels = [];
        $revenueLast7DaysData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueLast7DaysLabels[] = $date->format('d/m');
            $revenue = Orders::where('status', 'completed') // Chỉ tính đơn hàng hoàn thành
                ->whereDate('created_at', $date)
                ->sum('total'); // Sử dụng cột 'total'
            $revenueLast7DaysData[] = $revenue;
        }

        // --- Dữ liệu cho Biểu đồ Tỷ lệ Trạng thái Đơn hàng ---
        // Các trạng thái từ OrderController: pending, shipping, completed, cancelled
        $orderStatusCounts = Orders::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')->all();

        $statusTranslations = [
            'pending' => 'Chờ xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            // Thêm các trạng thái khác nếu có
        ];

        $orderStatusLabels = [];
        $orderStatusData = [];
        foreach ($orderStatusCounts as $status => $count) {
            $orderStatusLabels[] = $statusTranslations[$status] ?? ucfirst($status);
            $orderStatusData[] = $count;
        }

        // --- Dữ liệu cho Sản phẩm bán chạy nhất (Top 5) ---
        // Sử dụng bảng 'order_details' với các cột 'product_id', 'order_id', 'quantity'
        // và bảng 'orders' có cột 'status'
        $bestSellingQuery = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed');

        if ($queryStartDate && $queryEndDate) {
            $bestSellingQuery->whereBetween('orders.created_at', [$queryStartDate, $queryEndDate]);
        }

        $bestSellingProductsRaw = $bestSellingQuery
            ->select('products.name as product_name', DB::raw('SUM(order_details.quantity) as total_quantity_sold'))
            ->groupBy('products.id', 'products.name') // Group by product ID and name
            ->orderByDesc('total_quantity_sold')
            ->limit(5) // Lấy top 5 sản phẩm
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
            ->where('orders.status', 'completed')
            ->select('order_details.product_id', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('order_details.product_id');

        if ($queryStartDate && $queryEndDate) {
            $salesSubquery->whereBetween('orders.created_at', [$queryStartDate, $queryEndDate]);
        }

        $leastSellingProductsRaw = DB::table('products')
            ->leftJoinSub($salesSubquery, 'sales', function ($join) {
                $join->on('products.id', '=', 'sales.product_id');
            })
            ->select('products.name as product_name', DB::raw('COALESCE(sales.total_sold, 0) as total_quantity_sold'))
            ->orderBy('total_quantity_sold', 'asc') // Sắp xếp theo số lượng bán (0 trước)
            ->orderBy('products.name', 'asc')       // Sau đó theo tên sản phẩm để nhất quán
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
            'totalOrders',
            'totalCustomers',
            'totalRevenueThisMonth',
            'newProducts',
            'revenueLast7DaysLabels',
            'revenueLast7DaysData',
            'orderStatusLabels',
            'orderStatusData',
            'bestSellingProductLabels', // Dữ liệu cho nhãn biểu đồ sản phẩm bán chạy
            'bestSellingProductData',    // Dữ liệu cho giá trị biểu đồ sản phẩm bán chạy
            'leastSellingProductLabels',
            'leastSellingProductData',
            'startDateInput', // Để điền lại form filter
            'endDateInput'    // Để điền lại form filter
        ));
    }
}
