@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách Kích thước</h2>
    <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary mb-3">Thêm kích thước</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kích thước</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sizes as $size)
            <tr>
                <td>{{ $size->id }}</td>
                <td>{{ $size->name }}</td>
                <td>
                    <a href="{{ route('admin.sizes.edit', $size->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
