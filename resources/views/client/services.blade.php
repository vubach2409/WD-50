@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')
    <!-- Start Why Choose Us Section -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row my-5">
                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/truck.svg') }}" alt="Giao hàng nhanh" class="imf-fluid">
                        </div>
                        <h3>Giao hàng nhanh</h3>
                        <p>Chúng tôi đảm bảo sản phẩm sẽ được giao đến bạn một cách nhanh chóng và an toàn. Đội ngũ của chúng tôi cam kết giao hàng đúng hẹn.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/bag.svg') }}" alt="Dễ dàng mua sắm" class="imf-fluid">
                        </div>
                        <h3>Dễ dàng mua sắm</h3>
                        <p>Truy cập vào website và dễ dàng duyệt qua các sản phẩm nội thất chất lượng. Chỉ với vài cú click, bạn có thể mua sắm nhanh chóng và tiện lợi.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/support.svg') }}" alt="Hỗ trợ 24/7" class="imf-fluid">
                        </div>
                        <h3>Hỗ trợ 24/7</h3>
                        <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn mọi lúc mọi nơi. Đội ngũ chăm sóc khách hàng của chúng tôi luôn đáp ứng mọi yêu cầu và thắc mắc của bạn.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/return.svg') }}" alt="Trả hàng không rắc rối" class="imf-fluid">
                        </div>
                        <h3>Trả hàng không rắc rối</h3>
                        <p>Chúng tôi cung cấp chính sách trả hàng linh hoạt và dễ dàng. Nếu bạn không hài lòng, quá trình trả hàng sẽ không gặp phải bất kỳ sự phiền phức nào.</p>
                    </div>
                </div>

                <div class="row my-5">
                    <div class="col-6 col-md-6 col-lg-3 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('clients/images/truck.svg') }}" alt="Giao hàng nhanh" class="imf-fluid">
                            </div>
                            <h3>Giao hàng nhanh</h3>
                            <p>Chúng tôi đảm bảo sản phẩm sẽ được giao đến bạn một cách nhanh chóng và an toàn. Đội ngũ của chúng tôi cam kết giao hàng đúng hẹn.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('clients/images/bag.svg') }}" alt="Dễ dàng mua sắm" class="imf-fluid">
                            </div>
                            <h3>Dễ dàng mua sắm</h3>
                            <p>Truy cập vào website và dễ dàng duyệt qua các sản phẩm nội thất chất lượng. Chỉ với vài cú click, bạn có thể mua sắm nhanh chóng và tiện lợi.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('clients/images/support.svg') }}" alt="Hỗ trợ 24/7" class="imf-fluid">
                            </div>
                            <h3>Hỗ trợ 24/7</h3>
                            <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn mọi lúc mọi nơi. Đội ngũ chăm sóc khách hàng của chúng tôi luôn đáp ứng mọi yêu cầu và thắc mắc của bạn.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('clients/images/return.svg') }}" alt="Trả hàng không rắc rối" class="imf-fluid">
                            </div>
                            <h3>Trả hàng không rắc rối</h3>
                            <p>Chúng tôi cung cấp chính sách trả hàng linh hoạt và dễ dàng. Nếu bạn không hài lòng, quá trình trả hàng sẽ không gặp phải bất kỳ sự phiền phức nào.</p>
                        </div>
                    </div>
                </div>
                <!-- End Why Choose Us Section -->

                <!-- Featured Products Section -->
                <h3 class="text-center mb-5">
                    Sản phẩm nổi bật
                </h3>
                <div class="popular-product">
                    <div class="container">
                        <div class="row justify-content-center">
                            @foreach ($popularProducts as $product)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 position-relative">
                                    <div class="product-item position-relative overflow-hidden">
                                        <!-- Product image -->
                                        <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none">
                                            <div class="product-image position-relative">
                                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">

                                                <!-- Labels for "New" and "Sale" -->
                                                @if ($product->is_new)
                                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">Mới</span>
                                                @elseif($product->price_sale < $product->price)
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
                                                            {{ number_format($product->price, 0, ',', '.') }}đ
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>

                                            <!-- Action button (View product) -->
                                            <div class="product-action-btn position-absolute top-50 start-50 translate-middle">
                                                <a href="{{ route('product.details', $product->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-2 py-2">
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
                <!-- End Featured Products Section -->
            </div>
        </div>
    </div>
@endsection