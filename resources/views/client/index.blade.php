@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')
    <!-- Start Product Section -->
    <div class="product-section">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="container">
            <div class="row">

                <!-- Start Column 1 -->
                <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                    <h2 class="mb-4 section-title">Được chế tác bằng vật liệu tuyệt vời.</h2>
                    <p class="mb-4">Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và
                        không muốn gì cả. Có kẻ vulputate muốn cơn đau bất chợt buồn bã. </p>
                    <p><a href="{{ route('products') }}" class="btn">Khám phá</a></p>
                </div>
                <!-- End Column 1 -->

                <!-- Start Column 2: Display products in 3 columns -->
                @foreach ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 position-relative">
                        <div class="product-item position-relative overflow-hidden">
                            <!-- Product image -->
                            <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none">
                                <div class="product-image position-relative">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                                        alt="{{ $product->name }}">

                                    <!-- Labels for "New" and "Sale" -->
                                    @if ($product->is_new)
                                        <span class="badge bg-success position-absolute top-0 start-0 m-2">Mới</span>
                                    @elseif($product->price_sale < $product->price)
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">Giảm giá</span>
                                    @endif
                                </div>

                                <!-- Product info -->
                                <div class="product-info p-3">
                                    <!-- Tên sản phẩm (không nền) -->
                                    <h5 class="product-title text-dark mb-1"
                                        style="font-size: 1rem; background-color: transparent;">
                                        {{ $product->name }}
                                    </h5>

                                    <!-- Giá sản phẩm (không nền) -->
                                    <p class="product-price mb-2" style="background-color: transparent;">
                                        <span
                                            class="text-danger fw-bold">{{ number_format($product->price_sale, 0, ',', '.') }}đ</span>
                                        @if ($product->price_sale < $product->price)
                                            <span class="text-muted text-decoration-line-through ms-2">
                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Action button (View product) -->
                                <div class="product-action-btn position-absolute top-50 start-50 translate-middle">
                                    <a href="{{ route('product.details', $product->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill px-2 py-2">
                                        <i class="bi bi-eye me-1"></i> Xem thêm
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach



            </div>
        </div>
    </div>

    <!-- End Product Section -->

    <!-- Start Why Choose Us Section -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <h2 class="section-title">Tại sao chọn chúng tôi</h2>
                    <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không muốn gì
                        cả. Có kẻ vulputate muốn cơn đau bất chợt buồn bã.</p>

                    <div class="row my-5">
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/truck.svg') }}" alt="Image" class="imf-fluid">
                                </div>
                                <h3>Giao hàng nhanh</h3>
                                <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và
                                    không muốn gì cả. Một số thì thốt lên.
                                </p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/bag.svg') }}" class="imf-fluid">
                                </div>
                                <h3>Dễ dàng mua sắm </h3>
                                <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và
                                    không muốn gì cả. Một số thì thốt lên.
                                </p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/support.svg') }}" alt="Image" class="imf-fluid">
                                </div>
                                <h3>Hỗ trợ 24/7</h3>
                                <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và
                                    không muốn gì cả. Một số thì thốt lên.
                                </p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/return.svg') }}" alt="Image" class="imf-fluid">
                                </div>
                                <h3>Trả hàng không rắc rối</h3>
                                <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và
                                    không muốn gì cả. Một số thì thốt lên.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="img-wrap">
                        <img src="{{ asset('clients/images/why-choose-us-img.jpg') }}" alt="Image" class="img-fluid">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Why Choose Us Section -->

    <!-- Start We Help Section -->
    <div class="we-help-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    <div class="imgs-grid">
                        <div class="grid grid-1"><img src="{{ asset('clients/images/img-grid-1.jpg') }}" alt="Untree.co">
                        </div>
                        <div class="grid grid-2"><img src="{{ asset('clients/images/img-grid-1.jpg') }}"></div>
                        <div class="grid grid-3"><img src="{{ asset('clients/images/img-grid-1.jpg') }}"></div>
                    </div>
                </div>
                <div class="col-lg-5 ps-lg-5">
                    <h2 class="section-title mb-4">Chúng tôi giúp bạn thiết kế nội thất hiện đại</h2>
                    <p>Cho đến khi nó dễ hơn cả thuốc trang điểm thông thường. Cho đến khi cuộc sống ghét những người chơi
                        ăn malesuada. Anh ta không muốn gì cả và không muốn gì cả. Có kẻ vulputate muốn cơn đau bất chợt
                        buồn bã. Trẻ em phải chịu đựng tuổi già đau buồn và căn bệnh đau buồn.

                    </p>

                    <ul class="list-unstyled custom-list my-4">
                        <li>Cho đến khi cuộc sống ghét những người chơi ăn malesuada</li>
                        <li>Cho đến khi cuộc sống ghét những người chơi ăn malesuada</li>
                        <li>Cho đến khi cuộc sống ghét những người chơi ăn malesuada</li>
                        <li>Cho đến khi cuộc sống ghét những người chơi ăn malesuada</li>
                    </ul>
                    <p><a herf="#" class="btn">Explore</a></p>
                </div>
            </div>
        </div>
    </div>
    <!-- End We Help Section -->

    <!-- Start Popular Product -->
    <div class="popular-product">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="{{ asset('clients/images/product-1.png') }}" alt="Image" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Ghế Bắc Âu</h3>
                            <p>Cho đến khi nó dễ hơn cả thuốc trang điểm thông thường. Cho đến khi tôi ghét cuộc sống</p>
                            <p><a href="#">Đọc thên</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="{{ asset('clients/images/product-2.png') }}" alt="Image" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Ghế Kruzo Aero</h3>
                            <p>Cho đến khi nó dễ hơn cả thuốc trang điểm thông thường. Cho đến khi tôi ghét cuộc sống</p>
                            <p><a href="#">Đọc thên</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="{{ asset('clients/images/product-3.png') }}" alt="Image" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Ghế Công Thái Học</h3>
                            <p>Cho đến khi nó dễ hơn cả thuốc trang điểm thông thường. Cho đến khi tôi ghét cuộc sống</p>
                            <p><a href="#">Đọc thên</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
