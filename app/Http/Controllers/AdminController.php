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
    public function index()
    {
        // Các thống kê khác
        $totalProducts = Product::count();
        $totalOrders = Orders::count();
        $totalCustomers = User::where('role', '!=', 'admin')->count();
        $totalRevenueThisMonth = Orders::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');
        $newProducts = Product::where('created_at', '>=', now()->subDays(30))->count();

        // Biểu đồ doanh thu 7 ngày gần nhất
        $revenueLast7DaysLabels = [];
        $revenueLast7DaysData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueLast7DaysLabels[] = $date->format('d/m');
            $revenue = Orders::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total');
            $revenueLast7DaysData[] = $revenue;
        }

        // Biểu đồ tỷ lệ trạng thái đơn hàng
        $orderStatusCounts = Orders::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')->all();

        $statusTranslations = [
            'pending' => 'Chờ xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $orderStatusLabels = [];
        $orderStatusData = [];
        foreach ($orderStatusCounts as $status => $count) {
            $orderStatusLabels[] = $statusTranslations[$status] ?? ucfirst($status);
            $orderStatusData[] = $count;
        }

        // ✅ Thống kê doanh thu của sản phẩm
        $topRevenueProducts = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereNotNull('order_details.product_id')
            ->select(
                'order_details.product_id',
                'order_details.product_name',
                DB::raw('SUM(order_details.price * order_details.quantity) as total_revenue')
            )
            ->groupBy('order_details.product_id', 'order_details.product_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

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
            'topRevenueProducts' // Thêm biến này vào view
        ));
    }
}
