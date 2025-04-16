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
        $request->validate([
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'variation_name' => 'required|string|max:255',
            'sku' => 'required|string|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'weight' => 'nullable|numeric|min:0',
        ], [
            'variation_name.required' => 'Tên biến thể không được để trống.',
            'variation_name.max' => 'Tên biến thể không được quá 255 ký tự.',
            'sku.required' => 'Mã SKU không được để trống.',
            'sku.unique' => 'Mã SKU đã tồn tại.',
            'price.required' => 'Giá không được để trống.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'stock.required' => 'Số lượng tồn kho không được để trống.',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min' => 'Số lượng tồn kho không thể nhỏ hơn 0.',
            'color_id.exists' => 'Màu sắc không hợp lệ.',
            'size_id.exists' => 'Kích thước không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg.',
            'image.max' => 'Ảnh không được lớn hơn 2MB.',
            'weight.numeric' => 'Trọng lượng phải là số.',
            'weight.min' => 'Trọng lượng không thể nhỏ hơn 0.',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product_variants', 'public');
        }

        $product->variants()->create($data);

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
        $request->validate([
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'variation_name' => 'required|string|max:255',
            'sku' => 'required|string|unique:product_variants,sku,' . $variant->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'weight' => 'nullable|numeric|min:0',
        ], [
            'variation_name.required' => 'Tên biến thể không được để trống.',
            'variation_name.max' => 'Tên biến thể không được quá 255 ký tự.',
            'sku.required' => 'Mã SKU không được để trống.',
            'sku.unique' => 'Mã SKU đã tồn tại.',
            'price.required' => 'Giá không được để trống.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'stock.required' => 'Số lượng tồn kho không được để trống.',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min' => 'Số lượng tồn kho không thể nhỏ hơn 0.',
            'color_id.exists' => 'Màu sắc không hợp lệ.',
            'size_id.exists' => 'Kích thước không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg.',
            'image.max' => 'Ảnh không được lớn hơn 2MB.',
            'weight.numeric' => 'Trọng lượng phải là số.',
            'weight.min' => 'Trọng lượng không thể nhỏ hơn 0.',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $data['image'] = $request->file('image')->store('product_variants', 'public');
        }

        $variant->update($data);

        return redirect()->route('admin.product_variants.index', $product)
            ->with('success', 'Biến thể được cập nhật thành công!');
    }
    
    public function trash(Product $product)
    {
        $variants = $product->variants()->onlyTrashed()->get();

        return view('admin.product_variants.trash', [
            'product' => $product,
            'variants' => $variants
        ]);
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();
        return redirect()->route('admin.product_variants.index', $product)
            ->with('success', 'Biến thể đã được xóa!');
    }
    public function restore(Product $product, $variantId)
    {
        $variant = ProductVariant::withTrashed()->findOrFail($variantId);

        $variant->restore();


        return redirect()->route('admin.product_variants.index', $product)
            ->with('success', 'Biến thể đã được khôi phục!');
    }
    public function forceDelete(Product $product, ProductVariant $variant)
    {
        // Xóa vĩnh viễn biến thể
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }
    
        $variant->forceDelete();
    
        return redirect()->route('admin.product_variants.index', $product)
            ->with('success', 'Biến thể đã bị xóa vĩnh viễn!');
    }
    
    public function productsWithVariants()
    {
        $products = Product::with('variants')->paginate(8);
        return view('admin.product_variants.list', compact('products'));
    }
    public function search(Request $request)
    {
        $query = Product::with('variants');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $products = $query->paginate(8);

        return view('admin.product_variants.list', compact('products'));
    }
    

}
