<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HistoryPaymentController extends Controller
{
    public function index(){
        $orders = Orders::where('user_id', Auth::id())
        ->with(['payment'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.historypayment.index', compact('orders'));
    }
    public function history_detail($id)
    {
        $order = Orders::where('user_id', Auth::id())
            ->with(['items.product', 'payment', 'user'])
            ->where('id', $id)
            ->firstOrFail();
    
        return view('admin.historypayment.detail', compact('order'));
    }

    public function show($orderId)
    {
        $order = Orders::with('items.product')->where('id', $orderId)->firstOrFail();
        $subtotal = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('admin.historypayment.invoice', compact('order','subtotal'));
    }
    //barryvdh/laravel-dompdf:
    public function downloadPDF($orderId)
    {
        $order = Orders::with('items.product')->where('id', $orderId)->firstOrFail();
        $subtotal = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        // Truyền biến isPDF để ẩn nút tải về
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.historypayment.invoice', [
            'order' => $order,
            'isPDF' => true,
            'subtotal' =>  $subtotal
        ])
        ->setPaper('A4')
        ->setOptions(['defaultFont' => 'DejaVu Sans']);
    
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }
}
