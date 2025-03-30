<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        return view('client.products');
    }

    public function show(Product $product)
    {
        $product->load(['variants.color', 'variants.size', 'category', 'brand']);
        return view('client.product-details', compact('product'));
    }
}
