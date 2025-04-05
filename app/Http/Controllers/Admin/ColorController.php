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
            'name' => 'required|unique:colors,name|max:255',
        ]);

        Color::create($request->all());
        return redirect()->route('admin.colors.index')->with('success', 'Thêm màu thành công!');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|max:255|unique:colors,name,' . $color->id,
        ]);

        $color->update($request->all());
        return redirect()->route('admin.colors.index')->with('success', 'Cập nhật màu thành công!');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Xóa màu thành công!');
    }
}