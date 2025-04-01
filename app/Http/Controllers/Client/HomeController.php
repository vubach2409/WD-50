<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm mới nhất (giả sử có model Product)
        $products = Product::latest()->take(6)->get();

        return view('client.index', compact('products'));
    }
}
