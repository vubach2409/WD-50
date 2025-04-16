<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        try {
            $products = Product::all();
            return view('client.products', compact('products'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading products. Please try again later.');
        }
    }
}
