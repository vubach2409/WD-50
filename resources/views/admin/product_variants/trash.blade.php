@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="text-primary">Biến thể đã xóa: {{ $product->name }}</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($variants->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> Chưa có biến thể nào bị xóa.
            </div>
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
                                <td class="align-middle">{{ number_format($variant->price, 0, ',', '.') }}đ</td>
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
                                <td class="align-middle">{{ $variant->size->name ?? 'Không có' }}</td>
                                <td class="align-middle">{{ $variant->stock }}</td>
                                <td class="align-middle">
                                    <form
                                        action="{{ route('admin.product_variants.restore', [$product->id, $variant->id]) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Khôi phục sản phẩm này?');">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-success">Khôi phục</button>
                                    </form>

                                    <form
                                        action="{{ route('admin.product_variants.forceDelete', [$product->id, $variant->id]) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="text-center">
            <a href="{{ route('admin.product_variants.index', $product->id) }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
@endsection
