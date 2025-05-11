@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-cogs me-2"></i>
                Chi tiết sản phẩm: {{ $product->name }}
            </h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Tên sản phẩm:</strong> {{ $product->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Giá:</strong> {{ number_format($product->price_sale) }} đ - <span class="text-muted text-decoration-line-through">{{ number_format($product->price) }} đ</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Danh mục:</strong> {{ $product->category->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Thương hiệu:</strong> {{ $product->brand->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Mô tả:</strong> {{ $product->description ?? 'Không có mô tả' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Ngày cập nhật:</strong> {{ $product->updated_at->format('d/m/Y') }}
                        </li>
                    </ul>
                </div>

                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm" style="max-width: 90%; max-height: 400px; object-fit: cover;">
                    @else
                        <div class="alert alert-warning w-100">{{ __('Không có hình ảnh') }}</div>
                    @endif
                </div>
            </div>

            <hr>

            <!-- Nút điều hướng -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3 px-4">Quay lại</a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning mt-3 px-4">Sửa</a>
                <a href="{{ route('admin.product_variants.index', ['product' => $product->id]) }}" class="btn btn-info mt-3 px-4">Biến thể</a>
            </div>
        </div>
    </div>
</div>
@endsection
