@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách biến thể của sản phẩm: {{ $product->name }}</h2>
    
    <a href="{{ route('admin.product_variants.create', $product->id) }}" class="btn btn-primary mb-3">Thêm biến thể</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Tên biến thể</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th>Màu sắc</th>
                <th>Kích thước</th>
                <th>Tồn kho</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @if ($variants->isEmpty())
                <tr>
                    <td colspan="8" class="text-center text-danger">Sản phẩm này chưa có biến thể nào.</td>
                </tr>
            @else
                @foreach ($variants as $variant)
                <tr>
                    <td>{{ $variant->sku }}</td>
                    <td>{{ $variant->variation_name }}</td>
                    <td>{{ number_format($variant->price, 0, ',', '.') }}đ</td>
                    <td>
                        @if($variant->image)
                        <img src="{{ asset('storage/' . $variant->image) }}" alt="Ảnh biến thể" width="80">
                        @else
                        Không có ảnh
                        @endif
                    </td>
                    <td>{{ optional($variant->color)->name ?? 'Không có' }}</td>
                    <td>{{ optional($variant->size)->name ?? 'Không có' }}</td>
                    <td>{{ $variant->stock }}</td>
                    <td>
                        <a href="{{ route('admin.product_variants.edit', [$product->id, $variant->id]) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.product_variants.destroy', [$product->id, $variant->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection
