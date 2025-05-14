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
    public function index(Request $request)
    {
        // Lấy số lượng đơn hàng theo từng trạng thái
        $orderCounts = [
            'all' => Orders::count(), // Thêm đếm tất cả đơn hàng
            'pending' => Orders::where('status', 'pending')->count(),
            'shipping' => Orders::where('status', 'shipping')->count(),
            'completed' => Orders::where('status', 'completed')->count(),
            'cancelled' => Orders::where('status', 'cancelled')->count(),
        ];

        // Xác định trạng thái đơn hàng để hiển thị
        $status = $request->get('status', 'all'); // Mặc định là 'all' nếu không có tham số

        // Lấy các đơn hàng dựa trên trạng thái được chọn
        $orders = ($status == 'all')
            ? Orders::with('user')->get() // Lấy tất cả nếu status là 'all'
            : Orders::where('status', $status)->with('user')->get();

        // Truyền cả số lượng đơn hàng và danh sách đơn hàng vào view
        return view('admin.order.index', compact('orders', 'orderCounts'));
    }

    // Cập nhật trạng thái đơn hàng và xử lý liên quan đến thanh toán và stock
    public function update(Request $request, Orders $order)
    {
        // nếu trạng thái là cancel thì không cho cập nhật nữa
        if ($order->status == 'cancelled') {
            return redirect()->route('admin.orders.show', ['order' => $order->id])
                ->with('error', 'Không thể cập nhật trạng thái của đơn hàng đã hủy!');
        }

        if ($order->status == 'completed') {
            return redirect()->route('admin.orders.show', ['order' => $order->id])
                ->with('error', 'Không thể cập nhật trạng thái của đơn hàng đã giao!');
        }

        // Kiểm tra xem trạng thái có hợp lệ không
        $request->validate([
            'status' => 'required|in:pending,shipping,completed,cancelled'
        ]);

        $newStatus = $request->status;

        // Chỉ cho phép chuyển từ pending sang shipping hoặc cancelled
        if ($order->status === 'pending' && !in_array($newStatus, ['shipping', 'cancelled'])) {
            return redirect()->route('admin.orders.show', ['order' => $order->id])
                ->with('error', 'Chỉ được phép chuyển từ trạng thái chờ xử lý sang đang giao hoặc đã hủy!');
            // Chỉ cho phép chuyển từ shippinh sang complete
        } else if ($order->status === 'shipping' && !in_array($newStatus, ['completed'])) {
            return redirect()->route('admin.orders.show', ['order' => $order->id])->with('error', 'Chỉ được phép chuyển từ đang giao sang đã giao');
        }

        // Nếu không phải trạng thái pending thì không cho cập nhật (đã xử lý ở trên)
        $order->status = $newStatus;
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
        $order = Orders::with('user', 'payment')->findOrFail($id);
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
            }
        }
    }
}