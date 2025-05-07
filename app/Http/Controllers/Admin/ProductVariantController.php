<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        'color_id' => [
            'required', 
            'exists:colors,id',
            Rule::unique('product_variants')->where(fn($q) => 
                $q->where('color_id', $request->color_id)
                  ->where('size_id', $request->size_id)
                  ->where('product_id', $product->id)
            ),
        ],
        'size_id' => [
            'required', 
            'exists:sizes,id',
            Rule::unique('product_variants')->where(fn($q) => 
                $q->where('color_id', $request->color_id)
                  ->where('size_id', $request->size_id)
                  ->where('product_id', $product->id)
            ),
        ],
        'variation_name' => 'required|string|max:255',
        'sku' => 'required|string|unique:product_variants,sku',
        'price' => [
            'required', 'numeric', 'min:0',
            function ($attribute, $value, $fail) use ($product) {
                $min = $product->price_sale ?? $product->price;
                $max = $product->price;
                if ($min > $max) [$min, $max] = [$max, $min];
                if ($value < $min || $value > $max) {
                    $fail("Giá biến thể phải nằm trong khoảng từ {$min} đến {$max}.");
                }
            },
        ],
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ], [
        'variation_name.required' => 'Tên biến thể không được để trống.',
        'variation_name.max' => 'Tên biến thể không được quá 255 ký tự.',
        'color_id.required' => 'Color không được để trống.',
        'size_id.required' => 'Size không được để trống.',
        'sku.required' => 'Mã SKU không được để trống.',
        'sku.unique' => 'Mã SKU đã tồn tại.',
        'price.required' => 'Giá không được để trống.',
        'price.numeric' => 'Giá phải là số.',
        'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
        'stock.required' => 'Số lượng tồn kho không được để trống.',
        'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
        'stock.min' => 'Số lượng tồn kho không thể nhỏ hơn 0.',
        'color_id.exists' => 'Màu sắc không hợp lệ.',
        'color_id.unique' => 'Biến thể với màu sắc này đã tồn tại trong sản phẩm.',
        'size_id.unique' => 'Biến thể kích thước này đã tồn tại trong sản phẩm.',
        'size_id.exists' => 'Kích thước không hợp lệ.',
        'image.image' => 'Tệp tải lên phải là hình ảnh.',
        'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg.',
        'image.max' => 'Ảnh không được lớn hơn 2MB.',
    ]);

    $data = $request->only(['variation_name', 'sku', 'price', 'stock', 'color_id', 'size_id']);

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $data['image'] = $request->file('image')->store('variants', 'public');
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
    $request->validate([
        'color_id' => [
            'required',
            'exists:colors,id',
            Rule::unique('product_variants')->where(fn($q) => $q
                ->where('color_id', $request->color_id)
                ->where('size_id', $request->size_id)
                ->where('product_id', $product->id)
                ->where('id', '!=', $variant->id)),
        ],
        'size_id' => [
            'required',
            'exists:sizes,id',
            Rule::unique('product_variants')->where(fn($q) => $q
                ->where('color_id', $request->color_id)
                ->where('size_id', $request->size_id)
                ->where('product_id', $product->id)
                ->where('id', '!=', $variant->id)),
        ],
        'variation_name' => 'required|string|max:255',
        'sku' => 'required|string|unique:product_variants,sku,' . $variant->id,
        'price' => [
            'required', 'numeric', 'min:0',
            function ($attribute, $value, $fail) use ($product) {
                $min = min($product->price, $product->price_sale ?? $product->price);
                $max = max($product->price, $product->price_sale ?? $product->price);
                if ($value < $min || $value > $max) {
                    $fail("Giá biến thể sản phẩm phải nằm trong khoảng từ {$min} đ đến {$max} đ.");
                }
            },
        ],
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ], [
        'variation_name.required' => 'Tên biến thể không được để trống.',
        'variation_name.max' => 'Tên biến thể không được quá 255 ký tự.',
        'color_id.required' => 'Color không được để trống.',
        'size_id.required' => 'Size không được để trống.',
        'sku.required' => 'Mã SKU không được để trống.',
        'sku.unique' => 'Mã SKU đã tồn tại.',
        'price.required' => 'Giá không được để trống.',
        'price.numeric' => 'Giá phải là số.',
        'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
        'stock.required' => 'Số lượng tồn kho không được để trống.',
        'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
        'stock.min' => 'Số lượng tồn kho không thể nhỏ hơn 0.',
        'color_id.exists' => 'Màu sắc không hợp lệ.',
        'color_id.unique' => 'Biến thể với màu sắc này đã tồn tại trong sản phẩm.',
        'size_id.unique' => 'Biến thể kích thước này đã tồn tại trong sản phẩm.',
        'size_id.exists' => 'Kích thước không hợp lệ.',
        'image.image' => 'Tệp tải lên phải là hình ảnh.',
        'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg.',
        'image.max' => 'Ảnh không được lớn hơn 2MB.',
    ]);

    $data = $request->only(['variation_name', 'sku', 'price', 'stock', 'color_id', 'size_id']);

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        if ($variant->image && Storage::disk('public')->exists($variant->image)) {
            Storage::disk('public')->delete($variant->image);
        }
        $data['image'] = $request->file('image')->store('variants', 'public');
    }

    $variant->update($data);
    $product->updateStock();

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
        $oldStock = $product->stock;

        $variant->delete();

        $product->updateStock();

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

    public function forceDelete(Product $product, $variantId)
    {
        $variant = ProductVariant::withTrashed()->where('product_id', $product->id)->findOrFail($variantId);

        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->forceDelete();
        $product->updateStock();

        return redirect()->route('admin.product_variants.trash', $product)
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
