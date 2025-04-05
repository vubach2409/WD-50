@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Danh sách Người dùng</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($users->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i> Chưa có người dùng nào.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th class="text-center align-middle">STT</th>
                        <th class="text-center align-middle">Tên</th>
                        <th class="text-center align-middle">Email</th>
                        <th class="text-center align-middle">Role</th>
                        <th class="text-center align-middle">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td class="text-center align-middle">{{ $index + 1 }}</td> 
                        <td class="text-center align-middle">{{ $user->name }}</td> 
                        <td class="text-center align-middle">{{ $user->email }}</td>
                        <td class="text-center align-middle">{{ $user->role }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">Chi tiết</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
