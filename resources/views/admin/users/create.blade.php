@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="text-primary mb-4">Thêm người dùng mới</h2>

    {{-- Hiển thị lỗi validation nếu có --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu nhập vào.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>  
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                oninvalid="this.setCustomValidity('Vui lòng điền vào trường này')"
                oninput="this.setCustomValidity('')">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Địa chỉ Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ email hợp lệ')"
                oninput="this.setCustomValidity('')">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required
                oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu')"
                oninput="this.setCustomValidity('')">
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required
                oninvalid="this.setCustomValidity('Vui lòng xác nhận mật khẩu')"
                oninput="this.setCustomValidity('')">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại hợp lệ')"
                oninput="this.setCustomValidity('')">
            @error('phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Vai trò</label>
            <select name="role" class="form-select" required
                oninvalid="this.setCustomValidity('Vui lòng chọn vai trò')"
                oninput="this.setCustomValidity('')">
                <option value="">-- Chọn vai trò --</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="nhanvien" {{ old('role') == 'nhanvien' ? 'selected' : '' }}>Nhân viên</option>
            </select>
            @error('role')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">Ảnh đại diện (tuỳ chọn)</label>
            <input type="file" name="avatar" class="form-control">
            @error('avatar')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Thêm người dùng</button>
    </form>
</div>
@endsection
