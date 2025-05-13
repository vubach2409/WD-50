<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Tổng số sản phẩm, đơn hàng, khách hàng
        $totalProducts = Product::count();
        $totalOrders = Orders::count();
        $totalRevenue = Orders::where('status', 'completed')->sum('total');
        $newProducts = Product::where('created_at', '>=', now()->subMonth())->count();

        // Lấy doanh thu theo từng ngày trong tháng
        $dailyRevenue = DB::table('orders')
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('status', 'completed')
            ->whereMonth('created_at', date('m')) // Lấy doanh thu của tháng hiện tại
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Khởi tạo mảng doanh thu cho từng ngày trong tháng
        $revenues = [];
        $labels = [];
        foreach ($dailyRevenue as $rev) {
            $revenues[] = $rev->total;
            $labels[] = $rev->date;  // Lưu ngày vào nhãn
        }

        // Nếu có ngày nào không có doanh thu, thêm vào doanh thu 0 cho ngày đó
        $daysInMonth = now()->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = now()->month . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);  // Định dạng ngày
            if (!in_array($date, $labels)) {
                $labels[] = $date;
                $revenues[] = 0;
            }
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'newProducts',
            'revenues',
            'labels'
        ));
    }
}
