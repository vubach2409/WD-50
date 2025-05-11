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

        // Lấy doanh thu theo từng tháng trong năm
        $monthlyRevenue = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->where('status', 'completed')
            ->whereYear('created_at', date('Y')) // Lấy doanh thu của năm hiện tại
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        // Khởi tạo mảng doanh thu cho 12 tháng với giá trị mặc định là 0
        $revenues = array_fill(1, 12, 0);
        foreach ($monthlyRevenue as $rev) {
            $revenues[$rev->month] = $rev->total;
        }

        // Mảng nhãn tháng
        $labels = [
            'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
            'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ];

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

