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
    public function index()
    {
        $cartItems = Carts::where('user_id', Auth::id())->with('product')->get();
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        return view('Client.checkout', compact('cartItems', 'totalPrice'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'consignee_address' => ['required', 'string', 'max:255'],
            'consignee_name' => ['required', 'string', 'max:255'],
            'consignee_phone' => ['required', 'string', 'max:10'],
        ]);

        DB::beginTransaction();

        try {
            $cartItems = Carts::where('user_id', Auth::id())->with('product')->get();
            $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

            // 🛒 Tạo đơn hàng
            $order = Orders::create([
                'user_id' => Auth::id(),
                'total' => $totalPrice,
                'consignee_address' => $request->consignee_address,
                'payment_method' => $request->payment_method,
                'consignee_name' => $request->consignee_name,
                'consignee_phone' => $request->consignee_phone,   
                'status' => 'pending'
            ]);
            // thêm sản phẩm vào đơn hàng
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);
            }
            

            // 🗑️ Xóa giỏ hàng sau khi đặt hàng thành công
            Carts::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('thankyou')->with('success', 'Đặt hàng thành công, chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('thankyou')->with('error', 'Đặt hàng thất bại!');
        }
    }
}
