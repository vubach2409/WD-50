<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = new Product($request->except('image'));

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product->update($request->except('image'));

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã bị xóa!');
    }
}
