<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::whereIn('role', ['user', 'admin', 'nhanvien'])->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Chỉ admin mới được quyền thêm user
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không có quyền thêm người dùng.');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Tạo mảng tên trường và label tiếng Việt
        $fields = [
            'name' => 'Họ tên',
            'email' => 'Địa chỉ Email',
            'password' => 'Mật khẩu',
            'password_confirmation' => 'Xác nhận mật khẩu',
            'phone' => 'Số điện thoại',
            'role' => 'Vai trò',
            'avatar' => 'Ảnh đại diện',
        ];

        // Tạo thông báo lỗi required tự động
        $messages = [];
        foreach ($fields as $field => $label) {
            $messages["{$field}.required"] = "Vui lòng điền vào trường $label.";
        }

        // Thêm các thông báo lỗi khác
        $messages = array_merge($messages, [
            'email.email' => 'Địa chỉ Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'role.in' => 'Vai trò không hợp lệ.',
            'avatar.image' => 'Ảnh đại diện phải là file ảnh.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
        ]);

        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => ['nullable', 'regex:/^(0|\+84)[0-9]{9}$/'], // Ví dụ regex số điện thoại Việt Nam
            'role' => 'required|in:user,nhanvien',
            'avatar' => 'nullable|image|max:2048',
        ], $messages);

        // Tạo user mới
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->phone = $validated['phone'] ?? null;
        $user->role = $validated['role'];

        // Xử lý ảnh đại diện nếu có
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('uploads/avatars'), $filename);
            $user->avatar = $filename;
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::select('id', 'name', 'email', 'role', 'avatar', 'phone', 'created_at', 'updated_at')
            ->where('id', $id)
            // ->whereNotIn('role', ['admin', 'nhanvien'])
            ->first();

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User không tồn tại hoặc là Admin(Nhân viên).');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Kiểm tra nếu người dùng hiện tại không phải admin thì không có quyền sửa
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không có quyền sửa người dùng.');
        }

        // Tìm người dùng không phải admin
        $user = User::find($id);

        // Kiểm tra người dùng tồn tại
        if (!$user || $user->role === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Không thể sửa Admin hoặc người dùng không tồn tại.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        // Kiểm tra nếu người dùng hiện tại không phải admin thì không có quyền sửa
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không có quyền sửa người dùng.');
        }

        // Tìm người dùng không phải admin
        $user = User::find($id);

        // Kiểm tra người dùng tồn tại
        if (!$user || $user->role === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Không thể sửa Admin hoặc người dùng không tồn tại.');
        }

        // Validate và cập nhật thông tin người dùng
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,nhanvien', // Chỉ cho phép vai trò user hoặc staff
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được cập nhật.');
    }

    public function destroy(string $id)
    {
        $user = User::where('role', '!=', 'admin')->find($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa Admin.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User đã được xóa.');
    }
}
