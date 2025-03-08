<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
        public function index(Product $product)
    {
        return view('admin.product_variants.index', [
            'product' => $product,
            'variants' => $product->variants
        ]);
    }
    public function create(Product $product)
    {
        return view('admin.product_variants.create', [
            'product' => $product,
            'colors' => Color::all(),
            'sizes' => Size::all()
        ]);
    }
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'variation_name' => 'required|string|max:255',
            'sku' => 'required|string|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'weight' => 'nullable|numeric|min:0', 

        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product_variants', 'public');
        }

        $product->variants()->create($data);
        $product->updateStock();

        return redirect()->route('admin.product_variants.index', $product)
            ->with('success', 'Biến thể được thêm thành công!');
    }
    public function edit(Product $product, ProductVariant $variant)
    {
        return view('admin.product_variants.edit', [
            'product' => $product,
            'variant' => $variant,
            'colors' => Color::all(),
            'sizes' => Size::all()
        ]);
    }
    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $data = $request->validate([
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'variation_name' => 'required|string|max:255',
            'sku' => 'required|string|unique:product_variants,sku,' . $variant->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'weight' => 'nullable|numeric|min:0', 

        ]);

        if ($request->hasFile('image')) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $data['image'] = $request->file('image')->store('product_variants', 'public');
        }

        $variant->update($data);
        $product->updateStock(); 

        return redirect()->route('admin.product_variants.index', $product)
            ->with('success', 'Biến thể được cập nhật thành công!');
    }
    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->delete();
        $product->updateStock(); 

        return redirect()->route('admin.product_variants.index', $product)
            ->with('success', 'Biến thể đã được xóa!');
    }
    public function productsWithVariants()
    {
        $products = Product::with('variants')->get();
        return view('admin.product_variants.list', compact('products'));
    }

}
