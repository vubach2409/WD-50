
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-user-circle me-2"></i>
                Thông tin người dùng
            </h4>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>ID:</strong> {{ $user->id }}
                </li>
                <li class="list-group-item">
                    <strong>Tên:</strong> {{ $user->name }}
                </li>
                <li class="list-group-item">
                    <strong>Email:</strong> {{ $user->email }}
                </li>
                <li class="list-group-item">
                    <strong>Vai trò:</strong> {{ $user->role }}
                </li>
                <li class="list-group-item">
                    <strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa có' }}
                </li>
            </ul>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>
@endsection
