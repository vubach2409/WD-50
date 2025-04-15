<?php

namespace App\Http\Controllers\Client;

use App\Models\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function show($orderId)
    {
        // Lấy đơn hàng và voucher liên quan
        $order = Orders::with('items.product', 'items.variant.color', 'items.variant.size', 'voucher') // Lưu ý thêm 'voucher'
                      ->where('id', $orderId)
                      ->firstOrFail();
    
        $subtotal = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    
        // Debug dữ liệu voucher nếu cần
        // dd($order->voucher);
    
        return view('client.invoice', compact('order', 'subtotal'));
    }
    
    //barryvdh/laravel-dompdf:
    public function downloadPDF($orderId)
    {
        $order = Orders::with('items.product')->where('id', $orderId)->firstOrFail();
        $subtotal = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        // Truyền biến isPDF để ẩn nút tải về
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('client.invoice', [
            'order' => $order,
            'isPDF' => true,
            'subtotal' =>  $subtotal
        ])
        ->setPaper('A4')
        ->setOptions(['defaultFont' => 'DejaVu Sans']);
    
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }
    
    
}
