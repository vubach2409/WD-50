@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách Màu sắc</h2>
    <a href="{{ route('admin.colors.create') }}" class="btn btn-primary mb-3">Thêm màu sắc</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Màu</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($colors as $color)
            <tr>
                <td>{{ $color->id }}</td>
                <td>{{ $color->name }}</td>
                <td>
                    <a href="{{ route('admin.colors.edit', $color->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" style="display:inline;">
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
