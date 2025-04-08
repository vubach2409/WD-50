<?php

namespace App\Http\Controllers\Client;

use App\Models\Carts;
use App\Models\Orders;
use App\Models\Product;
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

            if ($payment){
                $payment->status = 'failed';
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
             return redirect()->route('account.orders')->with('success','Đơn hàng đã được huỷ thành công');
        }else {
            // Nếu đơn hàng không ở trạng thái 'pending', không thể hủy
            return redirect()->route('admin.orders.index')->with('error', 'Không thể hủy đơn hàng này vì trạng thái không phải là "Chờ xác nhận".');
        }
    }
    
}
