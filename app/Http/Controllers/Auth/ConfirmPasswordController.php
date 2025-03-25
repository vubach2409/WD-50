<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    /**
     * Where to redirect users after password confirmation.
     *
     * @var string
     */
    protected $redirectTo = '/login'; 

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Xác nhận mật khẩu trước khi tiếp tục.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Auth::guard()->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            return back()->withErrors(['password' => 'Mật khẩu không chính xác.']);
        }

        session(['auth.password_confirmed_at' => time()]);

        return redirect()->intended($this->redirectPath())->with('success', 'Xác nhận mật khẩu thành công!');
    }
}
