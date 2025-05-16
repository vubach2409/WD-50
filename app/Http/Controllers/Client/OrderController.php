<?php

namespace App\Http\Controllers\Client;

use App\Models\Carts;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Feedbacks;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
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

        $order->load(['items.product','items.variant', 'payment','ship', 'items.variant.color', 'items.variant.size']);
        return view('client.account.orders.show', compact('order'));
    }
    
    public function showOrder()
    {
        $userId = Auth::id(); // hoặc auth()->id()
    
        $ordersByStatus = [
            'pending' => Orders::where('user_id', $userId)->where('status', 'pending')->get(),
            'completed' => Orders::where('user_id', $userId)->where('status', 'completed')->get(),
            'cancelled' => Orders::where('user_id', $userId)->where('status', 'cancelled')->get(),
            'shipping' => Orders::where('user_id', $userId)->where('status', 'shipping')->get(),
        ];
    
        return view('client.account.orders.index', compact('ordersByStatus'));
    }
    public function cancelOrder($id){
        // Tìm đơn hàng cần huỷ

        $order = Orders::findOrFail($id);

        //Kiểm tra trạng thái có phải là pendding k

        if($order->status =='pending'){
            // Cập nhật trạng thái cancelled
            $order->status = 'cancelled';
            $order->save();

            //Kiểm tra đơn hàng có thanh toán

            $payment = $order->payment;

            //Nếu có thanh toán cập nhật trạng thái thành 'cancelled'

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



            // Quản lý số lượng tồn kho
            foreach ($order->items as $orderItem) {
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
            
            // Trả về phản hồi
            if ($order->payment_method === 'vnpay') {
                return redirect()
                    ->route('account.orders')
                    ->with('success', 'Đơn hàng đã được huỷ thành công. Vì bạn thanh toán bằng VNPAY, chúng tôi sẽ xử lý hoàn tiền sớm nhất cho quý khách.');
            }

            return redirect()
                ->route('account.orders')
                ->with('success', 'Đơn hàng đã được huỷ thành công.');
        }else {
            // Nếu đơn hàng không ở trạng thái 'pending', không thể hủy
            return redirect()->route('account.orders')->with('error', 'Không thể hủy đơn hàng này vì trạng thái không phải là "Chờ xác nhận".');
        }
    }
    // OrderController.php
    public function feedbackForm(Orders $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }
    
        if ($order->status !== 'completed') {
            return redirect()->route('orders.index')->with('error', 'Chỉ có thể đánh giá đơn hàng đã giao.');
        }
    
        // Kiểm tra đã đánh giá chưa
        $hasRated = Feedbacks::where('order_id', $order->id)
                            ->where('user_id', auth()->id())
                            ->exists();
    
        if ($hasRated) {
            return redirect()->route('orders.index')->with('error', 'Bạn đã đánh giá đơn hàng này rồi.');
        }
    
        $products = $order->items()->with('product')->get();
    
        return view('client.feedback', compact('order', 'products'));
    }
    

public function submitFeedback(Request $request, Orders $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    foreach ($request->feedbacks as $productId => $feedback) {
        Feedbacks::updateOrCreate([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'product_id' => $productId,
        ], [
            'star' => $feedback['star'],
            'content' => $feedback['content'],
        ]);
    }

    return redirect()->route('account.orders')->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
}
}
