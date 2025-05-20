<?php

namespace App\Http\Controllers\Client;

use App\Models\Feedbacks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'feedbacks' => 'required|array',
            'feedbacks.*.star' => 'required|integer|min:1|max:5',
            'feedbacks.*.content' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();

        foreach ($request->feedbacks as $variationId => $feedback) {
            // Kiểm tra đã đánh giá chưa
            $exists = Feedbacks::where('user_id', $userId)
                ->where('order_id', $orderId)
                ->where('variation_id', $variationId)
                ->exists();

            if ($exists) {
                continue; // Bỏ qua nếu đã có feedback
            }

            Feedbacks::create([
                'user_id' => $userId,
                'order_id' => $orderId,
                'variation_id' => $variationId,
                'star' => $feedback['star'],
                'content' => $feedback['content'] ?? null,
            ]);
        }

        return redirect()->route('orders.show', $orderId)->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}

