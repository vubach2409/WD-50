<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['variants.color', 'variants.size', 'category', 'brand']);
        return view('client.product-details', compact('product'));
    }
}
