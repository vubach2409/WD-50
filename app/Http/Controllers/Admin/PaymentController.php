<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    // AdminController hoặc OrdersController
public function showOrderPayments()
{
    $orders = Orders::with('payment')->orderBy('created_at', 'desc')->get();

    return view('admin.payment.index', compact('orders'));
}
// AdminController
public function updatePaymentStatus(Request $request, $orderId)
{
    $order = Orders::with('payment')->where('id', $orderId)->firstOrFail();
    
    // Cập nhật trạng thái thanh toán
    $order->payment->status = $request->input('status');
    $order->payment->save();
    
    return redirect()->route('admin.payment.show')->with('success', 'Cập nhật trạng thái thanh toán thành công.');
}
// AdminController
public function filterOrders(Request $request)
{
    $query = Orders::with('payment');

    // Kiểm tra nếu có mã giao dịch trong request
    if ($request->has('transaction_id') && !empty($request->transaction_id)) {
        $query->whereHas('payment', function($q) use ($request) {
            $q->where('transaction_id', 'like', '%' . $request->transaction_id . '%');
        });
    }

    $orders = $query->orderBy('created_at', 'desc')->get();

    return view('admin.payment.index', compact('orders'));
}



}
