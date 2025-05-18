@extends('layouts.user')

@section('title', 'Thanh toán')

@section('content')
    <div class="container py-5">
        <div class="row d-flex flex-wrap">
            <!-- Form Shipping Information -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Thông tin giao hàng</h2>
                        <form onsubmit="return confirm('Xác nhận đặt hàng?')" action="{{ route('checkout.process') }}"
                            method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="consignee_name" value="{{ old('name', auth()->user()->name ?? '') }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control @error('consignee_phone') is-invalid @enderror"
                                    name="consignee_phone" value="{{ old('consignee_phone') }}" required>
                                @error('consignee_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Shipping Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quận</label>
                                    <input type="text" class="form-control @error('subdistrict') is-invalid @enderror"
                                        name="subdistrict" value="{{ old('subdistrict') }}" required>
                                    @error('subdistrict')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Thành phố</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Địa chỉ cụ thể</label>
                                <input type="text" class="form-control @error('consignee_address') is-invalid @enderror"
                                    name="consignee_address" value="{{ old('consignee_address') }}" required>
                                @error('consignee_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Chọn phương thức vận chuyển:</label>
                                @foreach ($shippings as $shipping)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="shipping_id"
                                            id="shipping_{{ $shipping->id }}" value="{{ $shipping->id }}"
                                            data-fee="{{ $shipping->fee }}" required>
                                        <label class="form-check-label" for="shipping_{{ $shipping->id }}">
                                            {{ $shipping->method }} - {{ number_format($shipping->fee, 0, ',', '.') }} VNĐ
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <!-- Nút VNPay -->
                                <button type="submit" name="redirect"
                                    class="btn btn-primary btn-sm shadow-sm d-flex align-items-center justify-content-center">
                                    <i class="bi bi-credit-card me-2"></i> VNPay
                                </button>

                                <!-- Nút COD -->
                                <button type="submit" name="cod"
                                    class="btn btn-success btn-sm shadow-sm d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash me-2"></i> COD
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Tóm tắt đơn hàng</h3>
                        <div class="order-items mb-4" style="max-height: 300px; overflow-y: auto;">

                            @php $subTotal = 0; @endphp
                            @foreach ($cartItems as $item)
                                @php
                                    $variant = $item->variant;
                                    $product = $item->product;
                                    $price = $variant->price;
                                    $image = $variant->image;
                                    $colorCode = $variant->color->code ?? '#000';
                                    $sizeName = $variant->size->name ?? '';
                                    $subTotal += $item->quantity * $price;
                                @endphp
                                <div class="d-flex align-items-start mb-3">
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                        class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="flex-grow-1 small">
                                        <div class="fw-semibold fs-6">{{ $product->name }}</div>

                                        <div class="text-muted">
                                            Màu: <span
                                                style="display:inline-block;width:12px;height:12px;background-color:{{ $colorCode }};border:1px solid #ccc;margin-right:4px;border-radius:3px;"></span>
                                            | Size: {{ $sizeName }} | SL: {{ $item->quantity }}
                                        </div>
                                        <div class="text-end fw-bold text-dark">
                                            Giá: {{ number_format($item->quantity * $price, 0, ',', '.') }} đ
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        @php
                            $voucher = session('voucher');
                            $discount = $voucher['discount'] ?? 0;
                        @endphp

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính</span>
                                <span>{{ number_format($subTotal, 0, ',', '.') }} VNĐ</span>
                            </div>

                            @if ($voucher)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Giảm giá ({{ $voucher['code'] }})</span>
                                    <span>-{{ number_format($discount, 0, ',', '.') }} VNĐ</span>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển</span>
                                <span id="order-summary-shipping">0 VNĐ</span>
                            </div>

                            <div class="d-flex justify-content-between border-top pt-2">
                                <strong>Tổng cộng</strong>
                                <strong id="order-summary-total">
                                    {{ number_format($subTotal - $discount, 0, ',', '.') }} VNĐ
                                </strong>
                            </div>

                            @if ($voucher)
                                <form action="{{ route('cart.remove-voucher') }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-x-circle me-1"></i> Huỷ mã
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript cập nhật phí vận chuyển -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const shippingRadios = document.querySelectorAll('input[name="shipping_id"]');
                const shippingFeeDisplay = document.getElementById("order-summary-shipping");
                const totalDisplay = document.getElementById("order-summary-total");
                let subTotal = {{ $subTotal }};
                let discount = {{ $discount }};

                shippingRadios.forEach(radio => {
                    radio.addEventListener("change", function() {
                        let shippingFee = parseInt(this.getAttribute("data-fee")) || 0;
                        let total = subTotal - discount + shippingFee;
                        shippingFeeDisplay.textContent = new Intl.NumberFormat('vi-VN').format(
                            shippingFee) + " VNĐ";
                        totalDisplay.textContent = new Intl.NumberFormat('vi-VN').format(total) +
                            " VNĐ";
                    });

                    if (radio.checked) {
                        radio.dispatchEvent(new Event("change"));
                    }
                });
            });
        </script>
    </div>
@endsection
