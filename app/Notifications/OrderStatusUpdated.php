<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Order;
use App\Models\Orders;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // Gửi qua cơ sở dữ liệu (hiển thị ở bell icon)
    }

   public function toDatabase($notifiable)
{
    $status = $this->order->status;
    $message = match($this->order->status) {
    'pending' => "Đơn hàng #{$this->order->id} đang được xử lý.",
    'shipping'    => "Đơn hàng #{$this->order->id} đã được giao cho đơn vị vận chuyển.",
    'completed'  => "Đơn hàng #{$this->order->id} đã được giao thành công.",
    'cancelled'  => "Đơn hàng #{$this->order->id} đã bị hủy.",
    default      => "Trạng thái đơn hàng #{$this->order->id} đã được cập nhật.",
};

    return [
        'order_id' => $this->order->id,
        'message' => $message
    ];
}

}

