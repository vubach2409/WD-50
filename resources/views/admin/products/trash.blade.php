@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Thùng rác - Sản phẩm</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($products->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i> Chưa có sản phẩm nào bị xóa.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th class="text-left">Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Kho</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="text-left align-middle">{{ $product->name }}</td>
                            <td class="align-middle">{{ number_format($product->price) }} đ</td>
                            <td class="align-middle">{{ $product->stock }}</td>
                            <td class="align-middle">{{ $product->category->name ?? 'Không có' }}</td>
                            <td class="align-middle">{{ $product->brand->name ?? 'Không có' }}</td>
                            <td class="align-middle">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" width="100" height="100" class="border">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Khôi phục sản phẩm này?')">Khôi phục</button>
                                </form>

                                <form action="{{ route('admin.products.forceDelete', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa vĩnh viễn sản phẩm này?')">Xóa vĩnh viễn</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
