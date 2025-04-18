<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate với thông báo lỗi tiếng Việt
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Thử đăng nhập
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Điều hướng theo vai trò
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        // Trả về lỗi nếu đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.'
        ])->withInput(); // giữ lại input đã nhập
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Bạn đã đăng xuất thành công!');
    }
}
