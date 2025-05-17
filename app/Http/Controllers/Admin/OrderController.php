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
    public function index(){
       $orders = Orders::with('user')->latest()->get();

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
    } else if($order->status === 'shipping' && !in_array($newStatus, ['completed'])){
        return redirect()->route('admin.orders.show',['order' => $order->id])->with('error','Chỉ được phép chuyển từ đang giao sang đã giao');

    }

    

    // Nếu không phải trạng thái pending thì không cho cập nhật (đã xử lý ở trên)
    // Cập nhật trạng thái đơn hàng
    $order->status = $newStatus;
    $order->save();


    $payment = $order->payment; // Lấy thông tin thanh toán liên quan đến đơn hàng
    // Kiểm tra nếu trạng thái đơn hàng là 'completed'
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
            // Trạng thái riêng cho VNPAY khi đơn bị hủy
            $payment->status = 'cancelled_pending_refund';
        } elseif ($order->status === 'completed') {
            $payment->status = 'success';
        } else {
            $payment->status = 'pending';
        }
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
     $user = $order->user; // giả sử đơn hàng có quan hệ belongsTo với user
    $user->notify(new OrderStatusUpdated($order));

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


}
