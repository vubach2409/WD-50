<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Hiển thị form yêu cầu đặt lại mật khẩu.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email'); // Hoặc trả về view React nếu bạn dùng SPA
    }

    /**
     * Gửi email chứa liên kết đặt lại mật khẩu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Thông báo lỗi tiếng Việt
        $messages = [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.exists' => 'Không tìm thấy tài khoản với địa chỉ email này.',
        ];

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'email' => 'required|email|exists:users,email', // Kiểm tra email có tồn tại trong cơ sở dữ liệu
        ], $messages);

        // Gửi liên kết đặt lại mật khẩu
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        // Phản hồi thành công
        if ($response == Password::RESET_LINK_SENT) {
            return redirect()->back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi vào email của bạn!');
        } else {
            // Phản hồi lỗi (ví dụ email không tồn tại)
            return back()->withErrors(['email' => trans($response)]);
        }
    }
}
