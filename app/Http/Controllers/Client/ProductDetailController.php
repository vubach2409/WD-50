<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedbacks;

class ProductDetailController extends Controller
{
    public function index(Product $product)
    {
        $product = Product::with([
            'variants.color',
            'variants.size',
            'category',
            'brand',
            'feedbacks' => function ($query) {
                $query->where('is_hidden', false)->latest();
            },
            'feedbacks.user'
        ])->findOrFail($product->id);
        
        $product->average_rating = round($product->feedbacks->avg('star'), 1);
        $product->reviews_count = $product->feedbacks->count();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
                            ->where('id', '!=', $product->id)
                            ->latest()
                            ->take(4)
                            ->get();
        
        return view('client.product-details', compact('product','relatedProducts'));        
    }
    public function comment(Request $request, Product $product)
{
    $request->validate([
        'star' => 'required|integer|min:1|max:5',
        'content' => 'required|string|max:1000',
    ]);

    Feedbacks::create([
        'user_id' => auth()->id(),
        'product_id' => $product->id,
        'order_id' => null, // hoặc logic kiểm tra đơn hàng nếu muốn
        'star' => $request->star,
        'content' => $request->content,
    ]);

    return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
}

}
