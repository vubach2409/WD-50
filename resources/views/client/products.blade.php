@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">

            {{-- THÔNG BÁO --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow"
                    role="alert" style="z-index: 9999;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow"
                    role="alert" style="z-index: 9999;">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- THANH LỌC --}}
            <form method="GET" action="{{ route('products') }}" class="mb-4 d-flex flex-wrap gap-2 align-items-center">
                <select name="category" class="form-select w-auto">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="sort" class="form-select w-auto">
                    <option value="">Sắp xếp theo</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                </select>

                <button type="submit" class="btn btn-outline-primary">Lọc</button>
            </form>

            {{-- DANH SÁCH SẢN PHẨM --}}
            <div class="row">
                @forelse ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 position-relative">
                        <a class="product-item d-block text-decoration-none"
                            href="{{ route('product.details', $product->id) }}">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid product-thumbnail"
                                    alt="{{ $product->name }}">
                                {{-- NHÃN --}}
                                @if ($product->is_new)
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">Mới</span>
                                @elseif($product->price_sale < $product->price)
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Giảm giá</span>
                                @endif
                            </div>

                            <h3 class="product-title mt-2 mb-1">{{ $product->name }}</h3>
                            <strong class="product-price d-block text-danger">
                                {{ number_format($product->price_sale, 0, ',', '.') }}đ
                                @if ($product->price_sale < $product->price)
                                    <span class="text-muted text-decoration-line-through ms-2">
                                        {{ number_format($product->price, 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </strong>

                            {{-- NÚT CHỌN SẢN PHẨM --}}
                            <a href="{{ route('product.details', $product->id) }}"
                                class="btn btn-select-product w-100 mt-2">
                                <i class="bi bi-eye me-1"></i> Chọn sản phẩm
                            </a>


                        </a>
                    </div>
                @empty
                    <p>Không có sản phẩm nào phù hợp.</p>
                @endforelse
            </div>

            {{-- PHÂN TRANG --}}
            <div class="mt-4">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>


@endsection
