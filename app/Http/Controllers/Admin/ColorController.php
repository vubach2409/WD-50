<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/|unique:colors,code',
        ], [
            'code.required' => 'Vui lòng chọn mã màu.',
            'code.unique' => 'Mã màu đã tồn tại.',
            'code.regex' => 'Mã màu phải đúng định dạng HEX, ví dụ: #FF0000.',
        ]);

        Color::create($request->only('code')); 

        return redirect()->route('admin.colors.index')->with('success', 'Thêm màu thành công!');
    }


    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'code' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/|unique:colors,code,' . $color->id,
        ], [
            'code.required' => 'Vui lòng chọn mã màu.',
            'code.unique' => 'Mã màu đã tồn tại.',
            'code.regex' => 'Mã màu phải đúng định dạng HEX, ví dụ: #FF0000.',
        ]);

        $color->update($request->only('code'));
        return redirect()->route('admin.colors.index')->with('success', 'Cập nhật màu thành công!');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Xóa màu thành công!');
    }
}
