<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductDetailController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['variants.color', 'variants.size', 'category', 'brand']);
        return view('client.product-details', compact('product'));
    }
}
