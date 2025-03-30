<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.account.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment']);
        return view('client.account.orders.show', compact('order'));
    }
} 