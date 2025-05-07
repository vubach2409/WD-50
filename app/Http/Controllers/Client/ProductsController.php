<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
    
        // Sắp xếp
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price_sale', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price_sale', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }
    
        $products = $query->paginate(9); // Hiển thị 12 sản phẩm mỗi trang
        $categories = Category::all(); // Load danh sách danh mục
    
        return view('client.products', compact('products', 'categories'));
    }
}
