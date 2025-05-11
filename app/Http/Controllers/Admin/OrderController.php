<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Models\Feedbacks;

class OrderController extends Controller
{
    // Hiển thị danh sách tất cả đơn hàng
    public function index(){
        $orders = Orders::with('user')->get();
        return view('admin.order.index', compact('orders'));
    }

    // Cập nhật trạng thái đơn hàng và xử lý liên quan đến thanh toán và stock
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
        } else if ($order->status == 'cancelled') {
            $payment->status = 'failed';
        } else {
            // Nếu trạng thái đơn hàng không phải 'completed', cập nhật thanh toán thành 'pending'
            $payment->status = 'pending';
        }

        // Lưu thay đổi trạng thái thanh toán
        $payment->save();
    } else {
        if ($order->status == 'cancelled') {
            $payment->status = 'failed';
        }
        $payment->save();
    }

    if ($order->status == 'completed') {
        foreach ($order->items as $orderItem) {
            if ($orderItem->variant_id) {
                // Nếu đơn hàng là từ biến thể sản phẩm
                $variant = ProductVariant::withTrashed()->find($orderItem->variant_id);
                if ($variant) {
                    // Trừ stock của biến thể
                    $variant->stock -= $orderItem->quantity;
                    $variant->save();

                    // Cập nhật lại stock của sản phẩm chính
                    $variant->product->updateStock();
                }
            } else {
                // Nếu đơn hàng là từ sản phẩm gốc
                $product = Product::withTrashed()->find($orderItem->product_id);
                if ($product) {
                    // Trừ stock của sản phẩm chính
                    $product->stock -= $orderItem->quantity;
                    $product->save();

                    // Cập nhật lại stock của sản phẩm chính
                    $product->updateStock();
                }
            }
        }
    }

    return redirect()->route('admin.orders.show', ['order' => $order->id])
        ->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
}


    // Hiển thị chi tiết đơn hàng
    public function show($id)
    {
        $order = Orders::with('user', 'payment', 'items.product')->findOrFail($id);
        return view('admin.order.show', compact('order'));
    }

    // Phục hồi stock khi đơn hàng bị hủy
    private function restoreStock($orderItem)
    {
        if ($orderItem->variant_id) {
            // Nếu đơn hàng là từ biến thể sản phẩm
            $variant = ProductVariant::withTrashed()->find($orderItem->variant_id);
            if ($variant) {
                $variant->stock += $orderItem->quantity;
                $variant->restore(); // Nếu trước đó bị soft delete
                $variant->save();
                $variant->product->updateStock(); // Đồng bộ lại stock của sản phẩm chính
            }
        } else {
            // Nếu đơn hàng là từ sản phẩm gốc
            $product = Product::withTrashed()->find($orderItem->product_id);
            if ($product) {
                $product->stock += $orderItem->quantity;
                $product->restore(); // Nếu bị soft delete
                $product->save();
                $product->updateStock(); // Đồng bộ lại stock của sản phẩm chính
            }
        }
    }

    // Trừ stock khi đơn hàng được đặt
    private function decreaseStockOnOrder(Orders $order)
    {
        foreach ($order->items as $orderItem) {
            if ($orderItem->variant_id) {
                // Nếu là biến thể sản phẩm, trừ stock của biến thể
                $variant = ProductVariant::find($orderItem->variant_id);
                if ($variant) {
                    $variant->stock -= $orderItem->quantity; // Giảm stock của biến thể
                    $variant->save(); // Lưu lại sự thay đổi stock của biến thể
                }
            } else {
                // Nếu là sản phẩm gốc, trừ stock của sản phẩm gốc
                $product = Product::find($orderItem->product_id);
                if ($product) {
                    $product->stock -= $orderItem->quantity; // Giảm stock của sản phẩm gốc
                    $product->save(); // Lưu lại sự thay đổi stock của sản phẩm gốc
                }
            }
        }

        // Sau khi trừ stock của các biến thể, đồng bộ lại stock của sản phẩm chính
        $this->syncProductStock($order);
    }

    // Đồng bộ lại stock của sản phẩm chính sau khi trừ stock của biến thể
    private function syncProductStock(Orders $order)
    {
        foreach ($order->items as $orderItem) {
            if ($orderItem->variant_id) {
                $variant = ProductVariant::find($orderItem->variant_id);
                if ($variant) {
                    $product = $variant->product;
                    $product->updateStock(); // Cập nhật stock của sản phẩm chính
                }
            } else {
                $product = Product::find($orderItem->product_id);
                if ($product) {
                    $product->updateStock(); // Cập nhật stock của sản phẩm chính
                }
            }
        }
    }
}
