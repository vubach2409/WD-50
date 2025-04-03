<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Orders::count();
        $totalCustomers = User::count();
        $totalRevenue = Orders::sum('total');
        // $topSellingProduct = Product::withCount('orders')->orderBy('quantity', 'desc')->first();
        $newProducts = Product::where('created_at', '>=', now()->subMonth())->count();

        // Truyền dữ liệu vào view
        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalOrders', 
            'totalCustomers', 
            'totalRevenue', 
            // 'topSellingProduct', 
            'newProducts'
        ));
    }
}

