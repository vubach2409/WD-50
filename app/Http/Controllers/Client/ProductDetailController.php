<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Feedbacks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductDetailController extends Controller
{
    public function index(Product $product)
    {
        // Load product với quan hệ
        $product = Product::with([
            'variants.color',
            'variants.size',
            'category',
            'brand',
            'feedbacks' => function ($query) {
                $query->where('is_hidden', false)->latest();
            },
            'feedbacks.user',
             'feedbacks.variation.color', 
        'feedbacks.variation.size'  
        ])->findOrFail($product->id);
        
        // Tính điểm trung bình và số lượng đánh giá
        $product->average_rating = round($product->feedbacks->avg('star'), 1);
        $product->reviews_count = $product->feedbacks->count();
        
        // Sản phẩm liên quan
        $relatedProducts = Product::where('category_id', $product->category_id)
                            ->where('id', '!=', $product->id)
                            ->latest()
                            ->take(4)
                            ->get();
        
        return view('client.product-details', compact('product', 'relatedProducts'));        
    }

  
}