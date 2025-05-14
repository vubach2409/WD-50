
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-cogs me-2"></i>
                Chi tiết biến thể: {{ $variant->name }}
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Chi tiết bên trái -->
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Tên sản phẩm: </strong> {{ $product->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Tên biến thể: </strong> {{ $variant->variation_name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Màu sắc: </strong> {{ $variant->color->name ?? 'Chưa chọn' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Kích thước: </strong> {{ $variant->size->name ?? 'Chưa chọn' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Giá: </strong> {{ number_format($variant->price) }} đ
                        </li>
                        <li class="list-group-item">
                            <strong>Tồn kho: </strong> {{ $variant->stock }}
                        </li>
                        <li class="list-group-item">
                            <strong>Ngày tạo: </strong> {{ $variant->created_at->format('d/m/Y') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Ngày cập nhật: </strong> {{ $variant->updated_at->format('d/m/Y') }}
                        </li>
                    </ul>
                </div>

                <!-- Ảnh bên phải -->
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    @if ($variant->image)
                        <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->name }}" class="img-fluid rounded shadow-sm" style="max-width: 100%; max-height: 350px; object-fit: cover;">
                    @else
                        <div class="alert alert-warning w-100 text-center">{{ __('Không có hình ảnh') }}</div>
                    @endif
                </div>
            </div>

            <hr>

            <!-- Nút -->
            <div class="text-center">
                <a href="{{ route('admin.product_variants.index', $variant->product_id) }}" class="btn btn-secondary mt-3">
                    Quay lại
                </a>
                <a href="{{ route('admin.product_variants.edit', [$variant->product_id, $variant->id]) }}" class="btn btn-warning mt-3">
                    Sửa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
