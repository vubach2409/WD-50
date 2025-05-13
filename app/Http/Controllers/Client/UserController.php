<?php

namespace App\Http\Controllers\Client;

use App\Models\Orders;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function transactionHistory()
    {
        $transactions = Payment::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('client.account.vnpayhistory.index', compact('transactions'));
    }


    public function index()
    {
        $user = Auth::user();
        // $orders = Orders::where('user_id', $user->id)->latest()->get();
        return view('client.account.index',compact('user'));
    }
    

public function update(Request $request)
{
    $user = User::find(Auth::id());

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'phone' => 'nullable|regex:/^[0-9\-\+\s\(\)]+$/|min:8|max:20',
        'current_password' => 'required_with:new_password',
        'new_password' => 'nullable|min:8|confirmed',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;

    if ($request->filled('current_password')) {
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
    }

    $user->save();

    return back()->with('success', 'Hồ sơ đã được cập nhật.');
}


}

    

