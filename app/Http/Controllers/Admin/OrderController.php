<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    public function index(){
        // Lấy tất cả đơn hàng, có thể thêm phân trang nếu cần
        $orders = Orders::with('user')->get();

        return view('admin.order.index', compact('orders'));
    }
    public function update(Request $request, Orders $order)
    {
        // nếu trạng thái là cancel thì không cho cập nhật nữa
        if ($order->status == 'cancelled') {
            return redirect()->route('admin.orders.show', ['order' => $order->id])
                ->with('error', 'Không thể cập nhật trạng thái của đơn hàng đã hủy!');
        }
        // Kiểm tra xem trạng thái có hợp lệ không
        $request->validate([
            'status' => 'required|in:pending,shipping,completed,cancelled'
        ]);

        // Cập nhật trạng thái đơn hàng
        $order->status = $request->status;
        $order->save();
        

        $payment = $order->payment; // Lấy thông tin thanh toán liên quan đến đơn hàng
        // Kiểm tra nếu trạng thái đơn hàng là 'completed'
        if ($payment && $payment->payment_method == 'cod') {
            if ($order->status == 'completed') {
                // Nếu trạng thái đơn hàng là 'completed', cập nhật thanh toán thành 'success'
                $payment->status = 'success';
            }
            else if($order->status == 'cancelled'){
                $payment->status = 'failed';
            }           
            else {
                // Nếu trạng thái đơn hàng không phải 'completed', cập nhật thanh toán thành 'pending'
                $payment->status = 'pending';
            }

            // Lưu thay đổi trạng thái thanh toán
            $payment->save();
        }else{
            if($order->status == 'cancelled'){
                $payment->status = 'failed';
            }
            $payment->save();
        }

        if ($order->status == 'cancelled') {
            foreach ($order->items as $orderItem) {
                if ($orderItem->variant_id) {
                    // Nếu đơn hàng là từ biến thể sản phẩm
                    $variant = ProductVariant::withTrashed()->find($orderItem->variant_id);
                    if ($variant) {
                        $variant->stock += $orderItem->quantity;
                        $variant->restore(); // Nếu trước đó bị soft delete
                        $variant->save();
                    }
                } else {
                    // Nếu đơn hàng là từ sản phẩm gốc
                    $product = Product::withTrashed()->find($orderItem->product_id);
                    if ($product) {
                        $product->stock += $orderItem->quantity;
                        $product->restore(); // Nếu bị soft delete
                        $product->save();
                    }
                }
            }
            
        }

        return redirect()->route('admin.orders.show')
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    public function show($id)
    {
        // Lấy đơn hàng theo ID
        $order = Orders::with('user','payment','items.product')->findOrFail($id);

        return view('admin.order.show', compact('order'));
    }
}
