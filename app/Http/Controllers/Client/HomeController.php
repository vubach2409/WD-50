<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả sản phẩm có kèm biến thể
        $products = Product::with('variants')->get();

        // Tính minPrice và maxPrice cho từng sản phẩm dựa trên giá của biến thể
        foreach ($products as $product) {
            $variantPrices = $product->variants->pluck('price');
            if ($variantPrices->isNotEmpty()) {
                $minPrice = $variantPrices->min();
                $maxPrice = $variantPrices->max();
            } else {
                $minPrice = null;
                $maxPrice = null;
            }
        }

        // Lọc ra các sản phẩm có minPrice khác null rồi sắp xếp tăng dần theo minPrice
        $products = $products->filter(function($p) {
            return $p->minPrice !== null;
        })->sortBy('minPrice')->values();

        // Lấy 3 sản phẩm ngẫu nhiên để hiển thị (cũng tính min max)
        $popularProducts = Product::with('variants')->inRandomOrder()->take(3)->get();
        foreach ($popularProducts as $product) {
            $variantPrices = $product->variants->pluck('price');
            if ($variantPrices->isNotEmpty()) {
                $product->minPrice = $variantPrices->min();
                $product->maxPrice = $variantPrices->max();
            } else {
                $product->minPrice = null;
                $product->maxPrice = null;
            }
        }
        return view('client.index', compact('products', 'popularProducts'));
    }
}
