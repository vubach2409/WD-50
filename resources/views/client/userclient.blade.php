@extends('layouts.user')

@section('title', 'Thông Tin Cá Nhân')

@section('content')
<div class="container">
    <div class="card shadow-sm p-4">
        <h2 class="text-center">Thông Tin Cá Nhân</h2>

        {{-- Hiển thị thông báo thành công --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="text-center mb-3">
            @php
                $avatarPath = $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png');
            @endphp
            <img src="{{ $avatarPath }}" alt="Avatar" class="rounded-circle" width="150">
        </div>

        <form method="POST" action="{{ route('userclient.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Họ Tên</label>
                <input type="text" name="name" class="form-control" 
                       value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" 
                       value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số Điện Thoại</label>
                <input type="text" name="phone" class="form-control" 
                       value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Thay đổi mật khẩu (Để trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh đại diện</label>
                <input type="file" name="avatar" class="form-control">
                @if($user->avatar)
                    <p class="mt-2">Ảnh hiện tại:</p>
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar cũ" class="rounded" width="100">
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>
@endsection
