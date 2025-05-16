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
        ]);

        $data = [
            'name'    => $request->first_name . ' ' . $request->last_name,
            'email'   => $request->email,
            'message' => $request->message,
        ];

        Mail::to('ndduc202@gmail.com')->send(new ContactNotification($data));

        return redirect()->route('contact')->with('success', 'Cảm ơn bạn đã liên hệ với chúng tôi!');
    }
}
