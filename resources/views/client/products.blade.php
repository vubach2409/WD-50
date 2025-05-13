@extends('layouts.user')

@section('title', 'Cửa hàng')

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

            <div class="row">
                {{-- FORM LỌC BÊN TRÁI --}}
                <div class="col-md-3 mb-4">
                    <form method="GET" action="{{ route('products') }}" class="p-3 rounded shadow-sm bg-light">

                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">Danh mục</label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" id="category_all"
                                        value="" {{ request('category') == '' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category_all">Tất cả</label>
                                </div>
                                @foreach ($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category"
                                            id="category_{{ $category->id }}" value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="category_{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">Sắp xếp</label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sort" id="sort_default"
                                        value="" {{ request('sort') == '' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sort_default">Mặc định</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sort" id="sort_price_asc"
                                        value="price_asc" {{ request('sort') == 'price_asc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sort_price_asc">Giá tăng dần</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sort" id="sort_price_desc"
                                        value="price_desc" {{ request('sort') == 'price_desc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sort_price_desc">Giá giảm dần</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sort" id="sort_newest"
                                        value="newest" {{ request('sort') == 'newest' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sort_newest">Mới nhất</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn btn-primary w-40 mt-2 fs-12 px-3 py-2 rounded-pill shadow-sm border-0 transition-all hover-shadow">
                                <i class="bi bi-funnel-fill me-1"></i> Lọc
                            </button>
                        </div>


                    </form>
                </div>

                {{-- DANH SÁCH SẢN PHẨM BÊN PHẢI --}}
                <div class="col-md-9">
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-12 col-sm-6 col-md-4 mb-4">
                                <div class="product-item position-relative overflow-hidden">
                                    <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none">
                                        <div class="product-image position-relative">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                                                alt="{{ $product->name }}">

                                            @if ($product->is_new)
                                                <span
                                                    class="badge bg-success position-absolute top-0 start-0 m-2">Mới</span>
                                            @elseif($product->price_sale < $product->price)
                                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">Hot</span>
                                            @endif
                                        </div>

                                        <div class="product-info p-3">
                                            <h5 class="product-title text-dark mb-1" style="font-size: 1rem;">
                                                {{ $product->name }}</h5>
                                            <p class="product-price mb-2">
                                                <span
                                                    class="text-danger fw-bold">{{ number_format($product->price_sale, 0, ',', '.') }}đ</span>
                                                @if ($product->price_sale < $product->price)
                                                    <span
                                                        class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="product-action-btn position-absolute top-50 start-50 translate-middle">
                                            <a href="{{ route('product.details', $product->id) }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-2 py-2">
                                                <i class="bi bi-eye me-1"></i> Xem thêm
                                            </a>
                                        </div>

                                    </a>
                                </div>
                            </div>


                        @empty
                            <p>Không có sản phẩm nào phù hợp.</p>
                        @endforelse
                    </div>
                </div>



                {{-- PHÂN TRANG --}}
                <div class="mt-4">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    @endsection
