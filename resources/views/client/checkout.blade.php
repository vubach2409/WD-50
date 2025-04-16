@extends('layouts.user')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title mb-4">Shipping Information</h2>
                    <form action="{{ route('checkout.place-order') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subdistrict" class="form-label">Subdistrict</label>
                                <input type="text" class="form-control @error('subdistrict') is-invalid @enderror" id="subdistrict" name="subdistrict" value="{{ old('subdistrict') }}" required>
                                @error('subdistrict')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="zip_code" class="form-label">ZIP Code</label>
                                <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" value="{{ old('zip_code') }}" required>
                                @error('zip_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Shipping Method</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="shipping_method" id="standard" value="standard" checked>
                                <label class="form-check-label" for="standard">
                                    Standard Shipping (5-7 days) - $5.00
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_method" id="express" value="express">
                                <label class="form-check-label" for="express">
                                    Express Shipping (2-3 days) - $15.00
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Payment Method</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    Cash on Delivery
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                <label class="form-check-label" for="paypal">
                                    PayPal
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">Order Summary</h3>

                    <div class="order-items mb-4">
                        @foreach($items as $item)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $item['name'] }}</h6>
                                <small class="text-muted">Quantity: {{ $item['quantity'] }}</small>
                                <div class="text-end">
                                    <small class="text-muted">${{ number_format($item['subtotal'], 2) }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span id="order-summary-shipping">$5.00</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <strong>Total</strong>
                            <strong id="order-summary-total">${{ number_format($total + 5, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
});

// Update shipping cost when shipping method changes
document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingCost = this.value === 'express' ? 15.00 : 5.00;
        const subtotal = {{ $total }};
        const total = subtotal + shippingCost;

        // Update shipping cost and total in the form section
        document.getElementById('shipping-cost').textContent = `$${shippingCost.toFixed(2)}`;
        document.getElementById('total-amount').textContent = `$${total.toFixed(2)}`;

        // Update shipping cost and total in the order summary section
        document.getElementById('order-summary-shipping').textContent = `$${shippingCost.toFixed(2)}`;
        document.getElementById('order-summary-total').textContent = `$${total.toFixed(2)}`;
    });
});
</script>
@endpush
@endsection
