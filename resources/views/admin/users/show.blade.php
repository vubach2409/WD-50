@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Thông tin người dùng</h2>
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Tên:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Vai trò:</strong> {{ $user->role }}</p>
        <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa có' }}</p>
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Quay lại</a>
    </div>
@endsection
