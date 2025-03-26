@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')
    <div class="untree_co-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <div class="row mb-5">
                        <div class="col-md-12">
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
                                                <td>{{ $item->product->name }} <strong class="mx-2">x</strong>
                                                    {{ $item->quantity }}
                                                </td>
                                                <td>{{ number_format($item->product->price, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong>
                                                </td>
                                                <td class="text-black">
                                                    {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                                    VNĐ</td>
                                            </tr>
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Order Total</strong>
                                                </td>
                                                <td class="text-black font-weight-bold">
                                                    <strong
                                                        class="total">{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                                        VNĐ</strong>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                                <div class="form-group">
                                    <a class="btn btn-info" name="confirm-cod" href="{{ route('confirm.cod') }}">Thanh toán
                                        COD</a>

                                    <a href="{{ route('confirm.vnpay') }}" class="btn btn-light"
                                        name="confirm-vnpay">VnPay</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
