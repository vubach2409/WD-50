<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RefundProcessed extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    // Sử dụng kênh database để lưu notification
    public function via($notifiable)
    {
        return ['database'];
    }

    // Dữ liệu lưu trong bảng notifications
    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Đơn hàng #' . $this->order->id . ' đã được hoàn tiền thành công.',
            'amount' => $this->order->total,
        ];
    }
}
