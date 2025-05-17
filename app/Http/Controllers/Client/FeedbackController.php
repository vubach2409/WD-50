<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'star' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string|max:1000',
        ]);

        $product->feedbacks()->create([
            'user_id' => auth()->id(),
            'order_id' => null, // Nếu bạn có kiểm tra đã mua thì gắn order_id vào
            'star' => $request->star,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi!');
    }

}
