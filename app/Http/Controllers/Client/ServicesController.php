<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Feedbacks;
use App\Models\Product;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        $popularProducts = Product::inRandomOrder()->take(3)->get();
        
        $feedbacks = Feedbacks::where('is_hidden', false)
        ->inRandomOrder()
        ->limit(3)
        ->get();
        return view('client.services', compact('popularProducts', 'feedbacks'));
    }
}
