<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactNotification;

class ContactController extends Controller
{
    public function index()
    {
        return view('client.contact');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'message'    => 'required|string|min:10',
        ], [
            'first_name.required' => 'Vui lòng nhập họ.',
            'last_name.required'  => 'Vui lòng nhập tên.',
            'email.required'      => 'Vui lòng nhập email.',
            'email.email'         => 'Email không đúng định dạng.',
            'message.required'    => 'Vui lòng nhập nội dung.',
            'message.min'         => 'Nội dung phải có ít nhất :min ký tự.',
        ], [
            'first_name' => 'họ',
            'last_name' => 'tên',
            'email' => 'địa chỉ email',
            'message' => 'nội dung',
        ]);

        $data = [
            'name'    => $request->first_name . ' ' . $request->last_name,
            'email'   => $request->email,
            'message' => $request->message,
        ];

        Mail::to('vinhnnph17909@fpt.edu.vn')->send(new ContactNotification($data));

        return redirect()->route('contact')->with('success', 'Cảm ơn bạn đã liên hệ với chúng tôi!');
    }
}
