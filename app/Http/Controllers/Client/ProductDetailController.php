<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            'feedbacks.user',
            'feedbacks.variation.color',
            'feedbacks.variation.size'
        ])->findOrFail($product->id);

        // Tính điểm trung bình và số lượng đánh giá
        $product->average_rating = $product->feedbacks->isNotEmpty() ? round($product->feedbacks->avg('star'), 1) : 0;
        $product->reviews_count = $product->feedbacks->count();

        // Khoảng giá biến thể (ưu tiên giá sale)
        $variantPrices = $product->variants->map(function ($variant) {
            return $variant->price_sale ?: $variant->price;
        });
        $minPrice = $variantPrices->min();
        $maxPrice = $variantPrices->max();

        // Sản phẩm liên quan
        $relatedProducts = Product::with('variants')
        ->where('id', '!=', $product->id)
        ->where('category_id', $product->category_id)
        ->inRandomOrder()
        ->take(3)
        ->get();
        foreach ($relatedProducts as $related) {
        $variantPrices = $related->variants->pluck('price');
        if ($variantPrices->isNotEmpty()) {
            $related->minPrice = $variantPrices->min();
            $related->maxPrice = $variantPrices->max();
        } else {
            $related->minPrice = null;
            $related->maxPrice = null;
        }
    }
        return view('client.product-details', compact(
            'product',
            'relatedProducts',
            'minPrice',
            'maxPrice'
        ));
    }
}
