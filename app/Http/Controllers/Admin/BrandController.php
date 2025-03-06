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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Brand::create($request->all());
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được thêm thành công!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update($request->all());
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được cập nhật!');
    }
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được xóa!');
    }
}
