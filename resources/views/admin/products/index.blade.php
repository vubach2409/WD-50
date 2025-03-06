@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách sản phẩm</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>Kho</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
                <th>Ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price) }} đ</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->brand->name }}</td>
                    <td><img src="{{ asset('storage/' . $product->image) }}" width="50"></td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        
                        <!-- Nút Biến thể -->
                        <a href="{{ route('admin.product_variants.create', $product->id) }}" class="btn btn-info btn-sm">Biến thể</a>

                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
