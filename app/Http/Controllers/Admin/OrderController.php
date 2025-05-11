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
    /**
     * Hiển thị danh sách đơn hàng với bộ lọc trạng thái.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Lấy trạng thái từ query string.
        $status = $request->query('status');

        // Đếm số lượng đơn hàng cho từng trạng thái.
        $pendingCount = Orders::where('status', 'pending')->count();
        $shippingCount = Orders::where('status', 'shipping')->count();
        $completedCount = Orders::where('status', 'completed')->count();
        $cancelledCount = Orders::where('status', 'cancelled')->count();
        $allCount = Orders::count(); // Đếm tổng số đơn hàng

        // Lấy các đơn hàng dựa trên trạng thái được lọc.
        if ($status && $status != 'all') {
            $orders = Orders::with('user')
                ->where('status', $status)
                ->latest() // Sắp xếp theo thời gian tạo mới nhất
                ->paginate(10); // Phân trang, hiển thị 10 đơn hàng trên mỗi trang
        } else {
            $orders = Orders::with('user')
                ->latest()
                ->paginate(10);
        }

        // Trả về view 'admin.order.index' cùng với dữ liệu cần thiết.
        return view('admin.order.index', compact('orders', 'pendingCount', 'shippingCount', 'completedCount', 'cancelledCount','allCount'));
    }

    /**
     * Cập nhật trạng thái của một đơn hàng cụ thể.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orders  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Orders $order)
    {
        // Nếu trạng thái là 'cancelled', không cho phép cập nhật nữa.
        if ($order->status == 'cancelled') {
            return redirect()->route('admin.orders.show', ['order' => $order->id])
                ->with('error', 'Không thể cập nhật trạng thái của đơn hàng đã hủy!');
        }

        // Kiểm tra xem trạng thái mới có hợp lệ không.
        $request->validate([
            'status' => 'required|in:pending,shipping,completed,cancelled'
        ]);

        // Cập nhật trạng thái đơn hàng.
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

        // Cập nhật lại số lượng sản phẩm trong kho khi hủy đơn hàng
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

    /**
     * Hiển thị chi tiết của một đơn hàng cụ thể.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        // Lấy đơn hàng theo ID, cùng với thông tin người dùng, thanh toán và các sản phẩm trong đơn hàng.
        $order = Orders::with('user', 'payment', 'items.product')->findOrFail($id);

        // Trả về view 'admin.order.show' cùng với thông tin đơn hàng.
        return view('admin.order.show', compact('order'));
    }
}
