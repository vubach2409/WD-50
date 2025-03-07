@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách sản phẩm có biến thể</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng biến thể</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->variants->count() }}</td>
                <td>
                    <a href="{{ route('admin.product_variants.index', $product->id) }}" class="btn btn-primary btn-sm">Xem biến thể</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
