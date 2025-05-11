<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $messages = [
        'name.required' => 'Vui lòng nhập họ tên.',
        'name.string' => 'Họ tên phải là chuỗi ký tự.',
        'name.max' => 'Họ tên không được vượt quá 255 ký tự.',
        'name.regex' => 'Họ tên chỉ được chứa chữ cái và khoảng trắng.',

        'email.required' => 'Vui lòng nhập địa chỉ email.',
        'email.string' => 'Email phải là chuỗi ký tự.',
        'email.email' => 'Định dạng email không hợp lệ.',
        'email.max' => 'Email không được vượt quá 255 ký tự.',
        'email.unique' => 'Email này đã được đăng ký.',

        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
        'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
    ];

    $request->validate([
        'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ], $messages);

    // Tạo user mới với role mặc định là 'user'
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user', // User đăng ký sẽ có role là "user"
    ]);

    return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
}

}
