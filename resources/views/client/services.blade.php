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
                            <img src="{{ asset('clients/images/truck.svg') }}" alt="Image" class="imf-fluid">
                        </div>
                        <h3>Giao hàng nhanh</h3>
                        <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không muốn
                            gì cả. Một số thì thốt lên.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/bag.svg') }}" alt="Image" class="imf-fluid">
                        </div>
                        <h3>Dễ dàng mua sắm</h3>
                        <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không muốn
                            gì cả. Một số thì thốt lên.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/support.svg') }}" alt="Image" class="imf-fluid">
                        </div>
                        <h3>Hỗ trợ 24/7</h3>
                        <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không muốn
                            gì cả. Một số thì thốt lên.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/return.svg') }}" alt="Image" class="imf-fluid">
                        </div>
                        <h3>Trả hàng không rắc rối</h3>
                        <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không muốn
                            gì cả. Một số thì thốt lên.</p>
                    </div>
                </div>

                <div class="row my-5">
                    <div class="col-6 col-md-6 col-lg-3 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('clients/images/truck.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Giao hàng nhanh</h3>
                            <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không
                                muốn
                                gì cả. Một số thì thốt lên.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('clients/images/bag.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Dễ dàng mua sắm</h3>
                            <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không
                                muốn
                                gì cả. Một số thì thốt lên.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('clients/images/support.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Hỗ trợ 24/7</h3>
                            <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không
                                muốn
                                gì cả. Một số thì thốt lên.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('clients/images/return.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Trả hàng không rắc rối</h3>
                            <p>Cho đến khi cuộc sống ghét những người chơi ăn malesuada. Anh ta không muốn gì cả và không
                                muốn
                                gì cả. Một số thì thốt lên.</p>
                        </div>
                    </div>
                </div>
                <!-- End Why Choose Us Section -->
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
                
                
            </div>
            </div>
        </div>
    </div>
</div>

@endsection
