<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $products = Product::orderBy('price','desc')->take(3)->get();
            $popularProducts = Product::inRandomOrder()->take(3)->get();
            return view('client.index', compact('products','popularProducts'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading products. Please try again later.');
        }
    }
}
