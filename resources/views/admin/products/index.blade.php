@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Danh sách Sản phẩm</h2>
    
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>
    <a href="{{ route('admin.products.trash') }}" class="btn btn-secondary mb-3">Thùng rác</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Nhập tên sản phẩm..." value="{{ request('search') }}">
            </div>
            <div class="col-lg-3 col-md-6 mb-2">
                <select name="category_id" class="form-control">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6 mb-2">
                <select name="brand_id" class="form-control">
                    <option value="">-- Chọn thương hiệu --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6 mb-2">
                <select name="price_range" class="form-control">
                    <option value="">-- Chọn khoảng giá --</option>
                    <option value="1-5000000" {{ request('price_range') == '1-5000000' ? 'selected' : '' }}>Dưới 5 triệu</option>
                    <option value="5000000-10000000" {{ request('price_range') == '5000000-10000000' ? 'selected' : '' }}>5 - 10 triệu</option>
                    <option value="10000000-20000000" {{ request('price_range') == '10000000-20000000' ? 'selected' : '' }}>10 - 20 triệu</option>
                    <option value="20000000-" {{ request('price_range') == '20000000-' ? 'selected' : '' }}>Trên 20 triệu</option>
                </select>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-success">Lọc</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Xóa lọc</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-bordered text-center" id="productsTable">
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
                        <td class="align-middle">{{ $product->category->name }}</td>
                        <td class="align-middle">{{ $product->brand->name }}</td>
                        <td class="align-middle">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="100" height="100" class="border">
                            @else
                                <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn?')">Xóa</button>
                            </form>
                            <a href="{{ route('admin.product_variants.index', ['product' => $product->id]) }}" class="btn btn-info">Biến thể</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
