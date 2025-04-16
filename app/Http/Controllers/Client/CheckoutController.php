<?php

namespace App\Http\Controllers\Client;

use App\Models\Carts;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Carts::where('user_id', Auth::id())->with('product')->get();
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        $shippings = Shipping::all();
        

        return view('Client.checkout', compact('cartItems', 'totalPrice','shippings'));



    }
}
