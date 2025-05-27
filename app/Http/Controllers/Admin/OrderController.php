<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders;
use App\Models\Product;
use App\Models\Feedbacks;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Notifications\RefundProcessed;
use App\Notifications\OrderStatusUpdated;

class OrderController extends Controller
{
    // Hiển thị danh sách tất cả đơn hàng
       public function index(Request $request)
    {
        $orderCounts = [
            'pending' => Orders::where('status', 'pending')->count(),
            'shipping' => Orders::where('status', 'shipping')->count(),
            'completed' => Orders::where('status', 'completed')->count(),
            'cancelled' => Orders::where('status', 'cancelled')->count(),
        ];

        // Lấy trạng thái từ request, mặc định là 'pending' (hoặc bạn muốn mặc định trạng thái khác)
        $status = $request->get('status', 'pending');

        // Lấy đơn hàng theo trạng thái được chọn
        $orders = Orders::where('status', $status)->with('user')->get();

        // Truyền dữ liệu đến view
        return view('admin.order.index', compact('orders', 'orderCounts'));
    }


    // Cập nhật trạng thái đơn hàng và xử lý liên quan đến thanh toán và stock
    public function update(Request $request, Orders $order)
{
    // Nếu đơn đã hủy hoặc đã hoàn thành thì không được cập nhật trạng thái
    if ($order->status == 'cancelled') {
        return redirect()->route('admin.orders.index', ['status' => $order->status])
            ->with('error', 'Không thể cập nhật trạng thái của đơn hàng đã hủy!');
    }

    if ($order->status == 'completed') {
        return redirect()->route('admin.orders.index', ['status' => $order->status])
            ->with('error', 'Không thể cập nhật trạng thái của đơn hàng đã giao!');
    }

    // Validate trạng thái mới hợp lệ
    $request->validate([
        'status' => 'required|in:pending,shipping,completed,cancelled'
    ]);

    $newStatus = $request->status;

    // Kiểm tra chuyển trạng thái hợp lệ
    if ($order->status === 'pending' && !in_array($newStatus, ['shipping', 'cancelled'])) {
        return redirect()->route('admin.orders.index', ['status' => $order->status])
            ->with('error', 'Chỉ được phép chuyển từ trạng thái chờ xử lý sang đang giao hoặc đã hủy!');
    } elseif ($order->status === 'shipping' && !in_array($newStatus, ['completed'])) {
        return redirect()->route('admin.orders.index', ['status' => $order->status])
            ->with('error', 'Chỉ được phép chuyển từ đang giao sang đã giao!');
    }

    // Cập nhật trạng thái đơn hàng
    $order->status = $newStatus;
    $order->save();

    // Cập nhật trạng thái thanh toán theo trạng thái đơn hàng
    $payment = $order->payment;
    if ($payment) {
        if ($payment->payment_method === 'cod') {
            switch ($order->status) {
                case 'completed':
                    $payment->status = 'success';
                    break;
                case 'cancelled':
                    $payment->status = 'failed';
                    break;
                default:
                    $payment->status = 'pending';
                    break;
            }
        } elseif ($payment->payment_method === 'vnpay') {
            if ($order->status === 'cancelled') {
                $payment->status = 'cancelled_pending_refund';
            } elseif ($order->status === 'completed') {
                $payment->status = 'success';
            }
        }
        $payment->save();
    }

    // Gửi notification cho user
    $user = $order->user;
    $user->notify(new OrderStatusUpdated($order));

    // Redirect về trang danh sách đơn hàng, giữ lại tab trạng thái mới
    return redirect()->route('admin.orders.index', ['status' => $newStatus])
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
  public function refund($id)
{
    $order = Orders::with('payment')->findOrFail($id);
    $payment = $order->payment;

    if (!$payment || $payment->status !== 'cancelled_pending_refund') {
        return back()->with('error', 'Đơn hàng chưa được thanh toán.');
    }

    if ($payment->refund_status === 'refunded') {
        return back()->with('error', 'Đơn đã được hoàn tiền.');
    }

      $payment->refund_status = 'refunded';
    $payment->status = 'refunded_successfully'; // Trạng thái riêng biệt cho đơn đã hoàn tiền
    $payment->save();
     // Gửi notification cho user
    if ($order->user) {
        $order->user->notify(new RefundProcessed($order));
    }

    return back()->with('success', 'Đã cập nhật trạng thái hoàn tiền.');
}

public function cancelledOrders(Request $request)
{
    $query = Orders::with('payment')
        ->where('status', 'cancelled')
        ->whereHas('payment', function ($q) use ($request) {
            $q->where('payment_method', 'vnpay');

            // Nếu có lọc theo mã giao dịch:
            if ($request->has('transaction_id')) {
                $q->where('transaction_id', 'like', '%' . $request->transaction_id . '%');
            }
        });

    $orders = $query->latest()->get();

    return view('admin.payment.index', compact('orders'));
}

 function getOrderStatusName($status)
    {
        switch ($status) {
            case 'pending':
                return 'Chờ xử lý';
            case 'shipping':
                return 'Đang giao hàng';
            case 'completed':
                return 'Đã giao hàng';
            case 'cancelled':
                return 'Đã hủy';
            default:
                return 'Không xác định';
        }
    }

    public function getPaymentStatusName($status)
    {
        switch ($status) {
            case 'pending':
                return 'Chờ thanh toán';
            case 'success':
                return 'Đã thanh toán';
            case 'failed':
                return 'Thất bại';
            default:
                return 'Không xác định';
        }
    }
}

