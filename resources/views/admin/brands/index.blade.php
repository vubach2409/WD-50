@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách thương hiệu</h2>
    <a href="{{ route('brands.create') }}" class="btn btn-success mb-3">Thêm thương hiệu</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên thương hiệu</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->description }}</td>
                <td>
                    <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
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
