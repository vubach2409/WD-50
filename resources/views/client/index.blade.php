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
                    <h2 class="mb-4 section-title">Sản phẩm chất lượng cao, thiết kế tinh tế.</h2>
                    <p class="mb-4">Chúng tôi cam kết mang đến cho bạn những sản phẩm nội thất được chế tác từ vật liệu
                        cao cấp, phù hợp với mọi không gian sống. Tạo nên một ngôi nhà hoàn hảo cho bạn và gia đình.</p>
                    <p><a href="{{ route('products') }}" class="btn">Khám phá ngay</a></p>
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
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">Hot</span>
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
                                            class="text-danger fw-bold">{{ number_format($product->price_sale, 0, ',', '.') }}đ
                                            -
                                        </span>
                                        @if ($product->price_sale < $product->price)
                                            <span class="text-danger fw-bold">
                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Action button (View product) -->
                                <div class="product-action-btn position-absolute top-50 start-50 translate-middle">
                                    <a href="{{ route('product.details', $product->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill px-2 py-2">
                                        <i class="bi bi-eye me-1"></i> Xem chi tiết
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
                    <h2 class="section-title">Tại sao nên chọn chúng tôi?</h2>
                    <p>Chúng tôi chuyên cung cấp những sản phẩm nội thất chất lượng cao, thiết kế hiện đại, đáp ứng nhu cầu
                        của mọi gia đình. Chọn chúng tôi để tạo nên không gian sống lý tưởng cho bạn.</p>

                    <div class="row my-5">
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/truck.svg') }}" alt="Image" class="imf-fluid">
                                </div>
                                <h3>Giao hàng nhanh chóng</h3>
                                <p>Chúng tôi cam kết giao hàng nhanh chóng và đúng hạn, giúp bạn nhanh chóng sở hữu sản phẩm
                                    yêu thích.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/bag.svg') }}" class="imf-fluid">
                                </div>
                                <h3>Mua sắm dễ dàng</h3>
                                <p>Chúng tôi cung cấp giao diện dễ sử dụng và phương thức thanh toán thuận tiện, giúp bạn có
                                    trải nghiệm mua sắm tuyệt vời.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/support.svg') }}" alt="Image" class="imf-fluid">
                                </div>
                                <h3>Hỗ trợ khách hàng 24/7</h3>
                                <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn bất cứ lúc nào với đội ngũ tư vấn chuyên nghiệp và tận
                                    tâm.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/return.svg') }}" alt="Image" class="imf-fluid">
                                </div>
                                <h3>Chế độ đổi trả dễ dàng</h3>
                                <p>Chúng tôi cung cấp chính sách đổi trả đơn giản, giúp bạn an tâm khi mua sắm với chúng
                                    tôi.</p>
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
                    <p>Chúng tôi cung cấp các giải pháp thiết kế nội thất hiện đại, tối ưu không gian sống và mang lại sự
                        thoải mái cho mọi gia đình. Với kinh nghiệm và sự sáng tạo, chúng tôi cam kết mang đến cho bạn không
                        gian sống hoàn hảo.</p>

                    <ul class="list-unstyled custom-list my-4">
                        <li>Thiết kế nội thất sáng tạo, hiện đại</li>
                        <li>Cung cấp các sản phẩm chất lượng cao, bền bỉ</li>
                        <li>Giải pháp tối ưu không gian sống</li>
                        <li>Đảm bảo sự hài lòng của khách hàng</li>
                    </ul>
<<<<<<< Updated upstream
                    <p><a href="{{ route('products') }}" class="btn">Khám phá ngay</a></p>
=======
                    <p><a href="{{ route('products') }}" class="btn">Khám phá</a></p>
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </div>
    <!-- End We Help Section -->
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
    <div class="popular-product">
        <div class="container">
            <div class="row justify-content-center">
                @foreach ($popularProducts as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 position-relative">
                        <div class="product-item position-relative overflow-hidden">
                            <!-- Product image -->
                            <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none">
                                <div class="product-image position-relative">
<<<<<<< Updated upstream
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                                        alt="{{ $product->name }}">

=======
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
    
>>>>>>> Stashed changes
                                    <!-- Labels for "New" and "Sale" -->
                                    @if ($product->is_new)
                                        <span class="badge bg-success position-absolute top-0 start-0 m-2">Mới</span>
                                    @elseif($product->price_sale < $product->price)
<<<<<<< Updated upstream
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">Hot</span>
                                    @endif
                                </div>

                                <!-- Product info -->
                                <div class="product-info p-3">
                                    <!-- Product name -->
                                    <h5 class="product-title text-dark mb-1"
                                        style="font-size: 1rem; background-color: transparent;">
                                        {{ $product->name }}
                                    </h5>

                                    <!-- Product price -->
                                    <p class="product-price mb-2" style="background-color: transparent;">
                                        <span
                                            class="text-danger fw-bold">{{ number_format($product->price_sale, 0, ',', '.') }}đ - </span>
                                        @if ($product->price_sale < $product->price)
                                            <span class="text-danger fw-bold">
=======
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">Giảm giá</span>
                                    @endif
                                </div>
    
                                <!-- Product info -->
                                <div class="product-info p-3">
                                    <!-- Product name -->
                                    <h5 class="product-title text-dark mb-1" style="font-size: 1rem; background-color: transparent;">
                                        {{ $product->name }}
                                    </h5>
    
                                    <!-- Product price -->
                                    <p class="product-price mb-2" style="background-color: transparent;">
                                        <span class="text-danger fw-bold">{{ number_format($product->price_sale, 0, ',', '.') }}đ</span>
                                        @if ($product->price_sale < $product->price)
                                            <span class="text-muted text-decoration-line-through ms-2">
>>>>>>> Stashed changes
                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </p>
                                </div>
<<<<<<< Updated upstream

                                <!-- Action button (View product) -->
                                <div class="product-action-btn position-absolute top-50 start-50 translate-middle">
                                    <a href="{{ route('product.details', $product->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill px-2 py-2">
                                        <i class="bi bi-eye me-1"></i> Xem chi tiết
=======
    
                                <!-- Action button (View product) -->
                                <div class="product-action-btn position-absolute top-50 start-50 translate-middle">
                                    <a href="{{ route('product.details', $product->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-2 py-2">
                                        <i class="bi bi-eye me-1"></i> Xem thêm
>>>>>>> Stashed changes
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
<<<<<<< Updated upstream

@endsection
=======
    

@endsection
>>>>>>> Stashed changes
