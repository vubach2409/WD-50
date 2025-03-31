<?php

namespace App\Http\Controllers\Client;

use App\Models\Orders;
use App\Models\Transaction;
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
        $transactions = Transaction::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('client.user', compact('transactions'));
    }

    public function showOrder()
    {
        // $user = Auth::user();
        // $orders = Orders::where('user_id', $user->id)->latest()->get();
        // $transactions = Transaction::where('user_id', $user->id)->latest()->get();
        // return view('client.account.index',compact('user','orders','transactions'));
        $orders = Orders::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('client.account.orders.index', compact('orders'));
    }

    public function show(Orders $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment','ship']);
        return view('client.account.orders.show', compact('order'));
    }
    

    public function index()
    {
        $user = Auth::user();
        $orders = Orders::where('user_id', $user->id)->latest()->get();
        $transactions = Transaction::where('user_id', $user->id)->latest()->get();
        return view('client.account.index',compact('user','orders','transactions'));
    }
    

    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // public function index()
    // {
    //     $user = Auth::user();
    //     return view('client.account.index', compact('user'));
    // }

    // public function update(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255|unique:users,email,' . $user->id,
    //         'current_password' => 'required_with:new_password',
    //         'new_password' => 'nullable|min:8|confirmed',
    //     ]);

    //     $user->name = $request->name;
    //     $user->email = $request->email;

    //     if ($request->filled('current_password')) {
    //         if (!Hash::check($request->current_password, $user->password)) {
    //             return back()->withErrors(['current_password' => 'The current password is incorrect.']);
    //         }

    //         $user->password = Hash::make($request->new_password);
    //     }

    //     $user->save();

    //     return back()->with('success', 'Profile updated successfully.');
    // }
// public function updateProfile(Request $request)
// {
//     $user = User::find(Auth::id());

//     // Validate dữ liệu đầu vào
//     $request->validate([
//         'name'     => 'required|string|max:255',
//         'email'    => 'required|email|unique:users,email,' . $user->id,
//         'phone'    => 'nullable|regex:/^[0-9]{10,15}$/',
//         'avatar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
//         'password' => 'nullable|string|min:6',
//     ]);

//     // Xử lý avatar
//     if ($request->hasFile('avatar')) {
//         // Xóa ảnh cũ nếu tồn tại
//         if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
//             Storage::disk('public')->delete($user->avatar);
//         }

//         // Lưu ảnh mới vào thư mục storage/app/public/client
//         $avatarPath = $request->file('avatar')->store('client', 'public');
//         $user->avatar = $avatarPath;
//         $user->save();
//     }

//     // Chuẩn bị dữ liệu cập nhật
//     $updateData = [
//         'name'  => $request->name,
//         'email' => $request->email,
//         'phone' => $request->filled('phone') ? $request->phone : $user->phone,
//     ];

//     // Chỉ cập nhật mật khẩu nếu người dùng nhập mật khẩu mới
//     if ($request->filled('password')) {
//         $updateData['password'] = Hash::make($request->password);
//     }

//     $user->update($updateData);

//     return redirect()->back()->with('success', 'Profile updated successfully.');
}

    

