<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // Hiển thị form nhập email để nhận link đặt lại mật khẩu
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Gửi email đặt lại mật khẩu
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Đã gửi email đặt lại mật khẩu!')
            : back()->with('error', 'Không thể gửi email!');
    }
}
