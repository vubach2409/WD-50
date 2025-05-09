<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',
            'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',
        ]);

        Brand::create($request->only('name', 'description'));
        return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu thành công!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',
            'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update($request->only('name', 'description'));
        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Xoá thương hiệu thành công!');
    }
}
