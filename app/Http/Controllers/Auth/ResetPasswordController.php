<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Hiển thị form đặt lại mật khẩu
     */
    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    /**
     * Xử lý đặt lại mật khẩu
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed', // Người dùng nhập mật khẩu mới
            'token'    => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::error("Email không tồn tại: " . $request->email);
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        // Cập nhật mật khẩu
        $user->password = Hash::make($request->password);
        $user->save();

        Log::info("Mật khẩu đã được đặt lại cho: " . $request->email);

        return redirect()->route('login')->with('success', 'Mật khẩu đã được cập nhật thành công!');
    }
}
