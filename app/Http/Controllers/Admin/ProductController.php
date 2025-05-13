<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category_id) {    
            $query->where('category_id', $request->category_id);
        }

        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->price_range) {
            [$min, $max] = explode('-', $request->price_range);
            if ($max) {
                $query->whereBetween('price', [$min, $max]);
            } else {
                $query->where('price', '>=', $min);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(8);

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $variants = $product->variants;
        return view('admin.products.show', compact('product', 'variants'));
    }
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id)
                                 ->where('brand_id', $request->brand_id); 
                }),
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_sale' => 'required|numeric|min:0|lte:price',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.max' => 'Tên sản phẩm không được quá 255 ký tự.',
            'name.unique' => 'Tên sản phẩm này đã tồn tại trong danh mục và thương hiệu đã chọn.',
<<<<<<< Updated upstream
            'price.required' => 'Giá max không được để trống.',
            'price.numeric' => 'Giá max phải là số.',
            'price.min' => 'Giá max phải lớn hơn hoặc bằng 0.',
            'price_sale.required' => 'Giá min không được để trống.',
            'price_sale.numeric' => 'Giá min phải là số.',
            'price_sale.min' => 'Giá min phải lớn hơn hoặc bằng 0.',
            'price_sale.lte' => 'Giá min phải nhỏ hơn hoặc bằng giá gốc.',
=======
            'price.required' => 'Giá sản phẩm không được để trống.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0.',
            'price_sale.required' => 'Giá khuyến mãi không được để trống.',
            'price_sale.numeric' => 'Giá khuyến mãi phải là số.',
            'price_sale.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
            'price_sale.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc.',
>>>>>>> Stashed changes
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg.',
            'image.max' => 'Ảnh không được lớn hơn 2MB.',
        ]);

        // Lưu sản phẩm
        $product = new Product($request->except('image'));

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) use ($request, $product) {
                    return $query->where('category_id', $request->category_id)
                                 ->where('brand_id', $request->brand_id)
                                 ->where('id', '!=', $product->id); 
                }),
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_sale' => 'required|numeric|min:0|lte:price',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.max' => 'Tên sản phẩm không được quá 255 ký tự.',
            'name.unique' => 'Tên sản phẩm này đã tồn tại trong danh mục và thương hiệu đã chọn.',
<<<<<<< Updated upstream
            'price.required' => 'Giá max không được để trống.',
            'price.numeric' => 'Giá max phải là số.',
            'price.min' => 'Giá max phải lớn hơn hoặc bằng 0.',
            'price_sale.required' => 'Giá min không được để trống.',
            'price_sale.numeric' => 'Giá min phải là số.',
            'price_sale.min' => 'Giá min phải lớn hơn hoặc bằng 0.',
            'price_sale.lte' => 'Giá min phải nhỏ hơn hoặc bằng giá gốc.',
=======
            'price.required' => 'Giá sản phẩm không được để trống.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0.',
            'price_sale.required' => 'Giá khuyến mãi không được để trống.',
            'price_sale.numeric' => 'Giá khuyến mãi phải là số.',
            'price_sale.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
            'price_sale.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc.',
>>>>>>> Stashed changes
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg.',
            'image.max' => 'Ảnh không được lớn hơn 2MB.',
        ]);

        // Cập nhật sản phẩm
        $product->update($request->except('image'));

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        $product->variants()->delete();

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công!');
    }
    public function trash()
    {
        $products = Product::onlyTrashed()->paginate(8);
        return view('admin.products.trash', compact('products'));
    }
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->restore();

        $product->variants()->onlyTrashed()->restore();

        return redirect()->route('admin.products.trash')->with('success', 'Sản phẩm và biến thể đã được khôi phục.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->variants()->onlyTrashed()->forceDelete();

<<<<<<< Updated upstream
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
=======
        if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
            Storage::disk('public')->delete('products/' . $product->image);
>>>>>>> Stashed changes
        }

        $product->forceDelete();

        return redirect()->route('admin.products.trash')->with('success', 'Sản phẩm đã bị xóa vĩnh viễn!');
    }

}