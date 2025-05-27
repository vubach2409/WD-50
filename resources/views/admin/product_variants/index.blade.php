@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Danh sách biến thể của sản phẩm: {{ $product->name }}</h2>

    <a href="{{ route('admin.product_variants.create', $product->id) }}" class="btn btn-primary mb-3">Thêm biến thể</a>
    <a href="{{ route('admin.product_variants.trash', $product->id) }}" class="btn btn-secondary mb-3 ml-2">Thùng rác</a>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <ul class="nav nav-tabs">
            @foreach ($colors as $colorCode => $variantsByColor)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ $activeColor === $colorCode ? 'active fw-bold' : '' }}"
                    href="{{ route('admin.product_variants.index', ['product' => $product->id, 'color' => $colorCode]) }}">
                    
                    <span style="display:inline-block; width: 20px; height: 20px; background-color: {{ $colorCode }}; border: 1px solid #ccc; margin-right: 8px; border-radius: 3px;"></span>
                    
                    <span>
                        ({{ $variantsByColor->count() }})
                    </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if ($variants->isEmpty())
        @if ($product->variants->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> Sản phẩm chưa có biến thể nào.
            </div>
        @else
        @endif
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th class="text-left">Tên biến thể</th>
                        <th>Giá</th>
                        <th>Ảnh</th>
                        <th>Màu sắc</th>
                        <th>Kích thước</th>
                        <th>Tồn kho</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($variants as $variant)
                        <tr>
                            <td class="align-middle">{{ $variant->sku }}</td>
                            <td class="text-left align-middle">{{ $variant->variation_name }}</td>
                            <td class="align-middle">{{ number_format($variant->price, 0, ',', ',') }} ₫</td>
                            <td class="align-middle">
                                @if ($variant->image)
                                    <img src="{{ asset('storage/' . $variant->image) }}" alt="Ảnh biến thể"
                                        width="100" height="100" class="border">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if ($variant->color)
                                    <div class="d-flex flex-column align-items-center">
                                        <div
                                            style="width: 40px; height: 40px; background-color: {{ $variant->color->code }}; border: 1px solid #ccc; border-radius: 4px;">
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ optional($variant->size)->name ?? 'Không có' }}</td>
                            <td class="align-middle">{{ $variant->stock }}</td>
                            <td class="align-middle">
                                <a href="{{ route('admin.product_variants.edit', [$product->id, $variant->id]) }}"
                                    class="btn btn-warning">Sửa</a>
                                <form
                                    action="{{ route('admin.product_variants.destroy', [$product->id, $variant->id]) }}"
                                    method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </form>
                                <a href="{{ route('admin.product_variants.show', ['product' => $product->id, 'variant' => $variant->id]) }}"
                                    class="btn btn-info">Chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('admin.products.index', ['product' => $product->id]) }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection
