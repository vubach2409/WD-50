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
        $totalCustomers = User::where('role', '!=', 'admin')->count();
        $totalRevenue = Orders::where('status', 'completed')->sum('total');
        $newProducts = Product::where('created_at', '>=', now()->subMonth())->count();

        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalOrders', 
            'totalCustomers', 
            'totalRevenue', 
            'newProducts'
        ));
    }

}

