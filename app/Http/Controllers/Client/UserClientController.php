<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserClientController extends Controller
{
    public function index()
    {
        return view('client.userclient', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|regex:/^[0-9]{10,15}$/',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|string|min:6',
        ]);

        // Xử lý avatar
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($user->avatar && Storage::exists('public/client/' . $user->avatar)) {
                Storage::delete('public/client/' . $user->avatar);
            }

            // Lưu ảnh mới vào thư mục storage/app/public/client
            $avatarPath = $request->file('avatar')->store('client', 'public');
            $user->avatar = $avatarPath;
        }

        // Cập nhật thông tin người dùng
        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->filled('phone') ? $request->phone : $user->phone,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
}
