<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->price_range) {
            [$min, $max] = explode('-', $request->price_range);
            if ($max) {
                $query->whereBetween('price', [$min, $max]);
            } else {
                $query->where('price', '>=', $min);
            }
        }

        // Lấy danh sách sản phẩm
        $products = $query->paginate(20);

        return view('client.products', compact('products'));
    }
    public function show($id)
    {
        $product = Product::with(['variants.color', 'variants.size', 'category', 'brand'])->findOrFail($id);
        return view('client.detail', compact('product'));
    }

}
