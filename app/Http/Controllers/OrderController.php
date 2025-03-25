<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        return view('order.index', compact('cartItems', 'totalPrice'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,bank'
        ]);

        DB::beginTransaction();

        try {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'status' => 'pending'
            ]);

            // Thêm sản phẩm vào đơn hàng
            foreach ($cartItems as $item) {
                Order::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect('/')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đặt hàng thất bại!');
        }
    }
}
