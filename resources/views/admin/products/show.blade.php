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
                            <strong>Khoảng giá:</strong> {{ number_format($product->price_sale) }} đ - <span class="text-muted text-decoration-line-through">{{ number_format($product->price) }} đ</span>
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

            <h3 class="text-center mt-4 text-primary">Số lượng biến thể sản phẩm: {{ $product->variants->count() }}</h3>
            @if ($variants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SKU</th>
                                <th>Hình ảnh</th>
                                <th>Màu sắc</th>
                                <th>Kích thước</th>
                                <th>Giá</th>
                                <th>Số lượng tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($variants as $index => $variant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $variant->sku }}</td>
                                    <td class="text-center"><img src="{{ asset('storage/' . $variant->image) }}" alt="Ảnh biến thể" width="100" height="100" class="border"></td>
                                    <td>{{ $variant->color->name }}</td>
                                    <td>{{ $variant->size->name }}</td>
                                    <td>{{ number_format($variant->price) }} đ</td>
                                    <td>{{ $variant->stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i> Sản phẩm này không có biến thể nào.
                </div>
            @endif

            <hr>

            <!-- Nút điều hướng -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3 px-4">Quay lại</a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning mt-3 px-4">Sửa</a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mt-3 px-4"
                        onclick="return confirm('Bạn có chắc chắn?')">Xóa</button>
                </form>
                <a href="{{ route('admin.product_variants.index', ['product' => $product->id]) }}" class="btn btn-info mt-3 px-4">Xem biến thể</a>
            </div>
        </div>
    </div>
</div>
@endsection
