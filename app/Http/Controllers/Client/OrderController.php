<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carts;
use App\Models\OrderDetail;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function showOrder()
    {
        // $user = Auth::user();
        // $orders = Orders::where('user_id', $user->id)->latest()->get();
        // $transactions = Transaction::where('user_id', $user->id)->latest()->get();
        // return view('client.account.index',compact('user','orders','transactions'));
        $orders = Orders::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('client.account.orders.index', compact('orders'));
    }
    public function show(Orders $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment','ship']);
        return view('client.account.orders.show', compact('order'));
    }
    
}
