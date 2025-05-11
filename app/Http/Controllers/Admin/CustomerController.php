<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIn('role', ['user', 'admin', 'nhanvien'])->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::select('id', 'name', 'email', 'role', 'avatar', 'phone', 'created_at', 'updated_at')
            ->where('id', $id)
            // ->whereNotIn('role', ['admin', 'nhanvien'])
            ->first();

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User không tồn tại hoặc là Admin(Nhân viên).');
        }

        return view('admin.users.show', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    // Kiểm tra nếu người dùng hiện tại không phải admin thì không có quyền sửa
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('admin.users.index')->with('error', 'Bạn không có quyền sửa người dùng.');
    }

    // Tìm người dùng không phải admin
    $user = User::find($id);

    // Kiểm tra người dùng tồn tại
    if (!$user || $user->role === 'admin') {
        return redirect()->route('admin.users.index')->with('error', 'Không thể sửa Admin hoặc người dùng không tồn tại.');
    }

    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, string $id)
{
    // Kiểm tra nếu người dùng hiện tại không phải admin thì không có quyền sửa
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('admin.users.index')->with('error', 'Bạn không có quyền sửa người dùng.');
    }

    // Tìm người dùng không phải admin
    $user = User::find($id);

    // Kiểm tra người dùng tồn tại
    if (!$user || $user->role === 'admin') {
        return redirect()->route('admin.users.index')->with('error', 'Không thể sửa Admin hoặc người dùng không tồn tại.');
    }

    // Validate và cập nhật thông tin người dùng
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:user,nhanvien', // Chỉ cho phép vai trò user hoặc staff
    ]);

    $user->update($request->only('name', 'email', 'role'));

    return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được cập nhật.');
}

    public function destroy(string $id)
    {
        $user = User::where('role', '!=', 'admin')->find($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa Admin.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User đã được xóa.');
    }



}


