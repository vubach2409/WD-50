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
        // dd($totalRevenueThisMonth, $revenueLast7DaysData);

        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalOrders', 
            'totalCustomers', 
            'totalRevenueThisMonth', 
            'newProducts',
            'revenueLast7DaysLabels',
            'revenueLast7DaysData',
            'orderStatusLabels',
            'orderStatusData'
        ));
    }

}

