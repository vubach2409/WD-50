<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Get all orders
        $orders = Order::all();

        foreach ($orders as $order) {
            // Create payment for each order
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'payment_method' => $order->payment_method,
                'status' => $this->getRandomStatus(),
                'transaction_id' => $this->generateTransactionId($order->payment_method),
                'payment_date' => Carbon::now()->subDays(rand(1, 30))
            ]);
        }
    }

    private function getRandomStatus()
    {
        $statuses = ['pending', 'completed', 'failed', 'refunded'];
        return $statuses[array_rand($statuses)];
    }

    private function generateTransactionId($paymentMethod)
    {
        $prefix = strtoupper(substr($paymentMethod, 0, 3));
        return $prefix . '-' . strtoupper(uniqid()) . '-' . time();
    }
} 