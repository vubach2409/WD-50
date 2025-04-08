@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')

    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row mb-5">
                <form class="col-md-12" method="post">
                    <div class="site-blocks-table">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif


                        <h2>Giỏ hàng của bạn</h2>
                        @if ($cartItems->isEmpty())
                            <div class="alert alert-warning">Giỏ hàng của bạn đang trống!</div>
                        @endif
                        @if ($cartItems->count())
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Biến thể</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('storage/' . ($item->variant->image ?? $item->product->image)) }}"
                                                    width="80">
                                            </td>
                                            <td>{{ $item->product->name }}</td>
                                            <td>
                                                @if ($item->variant)
                                                    {{ $item->variant->color->name ?? '' }} -
                                                    {{ $item->variant->size->name ?? '' }}
                                                @else
                                                    Không có biến thể
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->variant->price, 0, ',', '.') }}
                                                VNĐ</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                        min="1" max="{{ $item->variant->stock }}"
                                                        class="form-control" style="width: 80px; display:inline-block;" />
                                                    <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                                                </form>
                                            </td>
                                            <td>
                                                {{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }}
                                                VNĐ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-5">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <form action="{{ route('cart.clear') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-black btn-sm btn-block">
                                            Clear entire cart</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('products') }}"><button
                                            class="btn btn-outline-black btn-sm btn-block">Continue Shopping</button></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="text-black h4" for="coupon">Coupon</label>
                                    <p>Enter your coupon code if you have one.</p>
                                </div>
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <input type="text" class="form-control py-3" id="coupon"
                                        placeholder="Coupon Code">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-black">Apply Coupon</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pl-5">
                            <div class="row justify-content-end">
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-12 text-right border-bottom mb-5">
                                            <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <span class="text-black">Subtotal</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <strong class="text-black">{{ number_format($totalPrice, 0, ',', '.') }}
                                                VNĐ</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <span class="text-black">Total</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <strong class="text-black">{{ number_format($totalPrice, 0, ',', '.') }}
                                                VNĐ</strong>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('checkout') }}"
                                                class="btn btn-black btn-lg py-3 btn-block">Proceed
                                                To
                                                Checkout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        @endif
    @endsection
