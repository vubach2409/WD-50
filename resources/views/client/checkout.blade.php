@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')

    {{-- <div class="container">
            <div class="row">
                <h2 class="h3 mb-3 text-black">Your Order</h2>
                <div class="p-3 p-lg-5 border bg-white">
                    <table class="table site-block-order-table mb-5">
                        <thead>
                            <th>Product</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }} <strong class="mx-2">x</strong> {{ $item->quantity }}
                                    </td>
                                    <td>{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                <td class="text-black">
                                    {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            {{-- <tr>
                                <td class="text-black font-weight-bold"><strong>Shipping</strong></td>
                                <td class="text-black">{{ number_format($shippingFee, 0, ',', '.') }} VNĐ</td>
                            </tr> --}}
    {{-- <tr>
                                <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                <td class="text-black font-weight-bold">
                                    <strong
                                        class="total">{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                        VNĐ</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <a class="btn btn-info" name="confirm-cod" href="{{ route('confirm.cod') }}">Thanh toán
                            COD</a>

                        <a href="{{ route('confirm.vnpay') }}" class="btn btn-light" name="confirm-vnpay">VnPay</a>

                        <a href="{{ route('cart.show') }}" class="btn btn-dark">Quay lại</a>
                    </div>
                </div>
            </div>
        </div> --}}





    <div class="container py-5">
        <div class="row d-flex flex-wrap">
            <!-- Form Shipping Information -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Shipping Information</h2>
                        <form onsubmit="return confirm('Xác nhận đặt hàng')" action="{{ route('checkout.process') }}"
                            method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="consignee_name"
                                        value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email"
                                        value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="consignee_phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control @error('consignee_phone') is-invalid @enderror"
                                    id="consignee_phone" name="consignee_phone" value="{{ old('consignee_phone') }}"
                                    required>
                                @error('consignee_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="subdistrict" class="form-label">Quận</label>
                                    <input type="text" class="form-control @error('subdistrict') is-invalid @enderror"
                                        id="subdistrict" name="subdistrict" value="{{ old('subdistrict') }}" required>
                                    @error('subdistrict')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">Thành phố</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="consignee_address" class="form-label">Địa chỉ cụ thể</label>
                                <input type="text" class="form-control @error('consignee_address') is-invalid @enderror"
                                    id="consignee_address" name="consignee_address" value="{{ old('consignee_address') }}"
                                    required>
                                @error('consignee_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Chọn phương thức vận chuyển:</label>
                                <div class="form-check mb-2">
                                    @foreach ($shippings as $shipping)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shipping_id"
                                                id="shipping_{{ $shipping->id }}" value="{{ $shipping->id }}"
                                                data-fee="{{ $shipping->fee }}" required>
                                            <label class="form-check-label" for="shipping_{{ $shipping->id }}">
                                                {{ $shipping->method }} - {{ number_format($shipping->fee, 0, ',', '.') }}
                                                VNĐ
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Payment Method</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="cod" value="cod">
                                    <label class="form-check-label" for="cod">
                                        Thanh toán khi nhận hàng
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="redirect" id="redirect"
                                        value="">
                                    <label class="form-check-label" for="redirect">
                                        Thanh toán vnpay
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Xác nhận</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Order Summary</h3>
                        <div class="order-items mb-4">
                            @foreach ($cartItems as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item['name'] }}"
                                        class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $item['name'] }}</h6>
                                        <small class="text-muted">Quantity: {{ $item['quantity'] }}</small>
                                        <div class="text-end">
                                            <small class="text-muted">
                                                {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                                VNĐ
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }} VNĐ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span id="order-summary-shipping">0 VNĐ</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2">
                                <strong>Total</strong>
                                <strong id="order-summary-total">
                                    {{ number_format($totalPrice, 0, ',', '.') }} VNĐ
                                </strong>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const shippingRadios = document.querySelectorAll('input[name="shipping_id"]');
            const shippingFeeDisplay = document.getElementById("order-summary-shipping");
            const totalDisplay = document.getElementById("order-summary-total");
            let totalPrice = parseInt("{{ $totalPrice }}");

            shippingRadios.forEach(radio => {
                radio.addEventListener("change", function() {
                    let shippingFee = parseInt(this.getAttribute("data-fee")) || 0;
                    let total = totalPrice + shippingFee;

                    shippingFeeDisplay.textContent = new Intl.NumberFormat('vi-VN').format(
                        shippingFee) + " VNĐ";
                    totalDisplay.textContent = new Intl.NumberFormat('vi-VN').format(total) +
                        " VNĐ";
                });

                // Chọn mặc định giá vận chuyển khi trang tải
                if (radio.checked) {
                    let event = new Event("change");
                    radio.dispatchEvent(event);
                }
            });
        });
    </script>


@endsection
