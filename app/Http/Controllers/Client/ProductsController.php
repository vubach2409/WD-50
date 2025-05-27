<?php
namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
    // Subquery để lấy min_price cho mỗi sản phẩm
    $subQuery = DB::table('product_variants')
        ->select('product_id', DB::raw('MIN(price) as min_price'))
        ->groupBy('product_id');

    $query = Product::with('variants')
        ->leftJoinSub($subQuery, 'variant_prices', function ($join) {
            $join->on('products.id', '=', 'variant_prices.product_id');
        })
        ->select('products.*', 'variant_prices.min_price');

    // Lọc theo danh mục
    if ($request->filled('category')) {
        $query->where('products.category_id', $request->category);
    }

    // Sắp xếp
    if ($request->filled('sort')) {
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('variant_prices.min_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('variant_prices.min_price', 'desc');
                break;
            case 'newest':
                $query->orderBy('products.created_at', 'desc');
                break;
        }
    }

    $products = $query->paginate(9);

    // Tính khoảng giá từ biến thể
    foreach ($products as $product) {
        $variantPrices = $product->variants->pluck('price');
        if ($variantPrices->isNotEmpty()) {
            $product->minPrice = $variantPrices->min();
            $product->maxPrice = $variantPrices->max();
        } else {
            $product->minPrice = null;
            $product->maxPrice = null;
        }
    }

    $categories = Category::all();

    return view('client.products', compact('products', 'categories'));
    }
}
