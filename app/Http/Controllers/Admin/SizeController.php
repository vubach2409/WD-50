<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
        ], [
            'name.required' => 'Vui lòng nhập tên kích thước.',
            'name.unique' => 'Kích thước này đã tồn tại.',
            'name.max' => 'Tên kích thước không được vượt quá 255 ký tự.',
        ]);

        Size::create($request->only('name'));
        return redirect()->route('admin.sizes.index')->with('success', 'Thêm kích thước thành công!');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
        ], [
            'name.required' => 'Vui lòng nhập tên kích thước.',
            'name.unique' => 'Kích thước này đã tồn tại.',
            'name.max' => 'Tên kích thước không được vượt quá 255 ký tự.',
        ]);

        $size->update($request->only('name'));
        return redirect()->route('admin.sizes.index')->with('success', 'Cập nhật kích thước thành công!');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Xoá kích thước thành công!');
    }
}
