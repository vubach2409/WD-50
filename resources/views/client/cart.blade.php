@extends('layouts.user')

@section('title', 'Giỏ hàng')

@section('content')

    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row mb-5">
                <form class="col-md-12" method="post">
                    <div class="site-blocks-table">
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <h2>Giỏ hàng của bạn</h2>
                        @if ($cartItems && $cartItems->isEmpty())
                            <div class="alert alert-warning">Giỏ hàng của bạn đang trống!</div>
                        @endif
                        @if ($cartItems && $cartItems->count())
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Biến thể</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th></th>
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
                                            <td>
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                        X
                                                    </button>
                                                </form>


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
                                        <button type="submit" class="btn btn-danger btn-sm btn-block">
                                            Xoá toàn bộ</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('products') }}"><button
                                            class="btn btn-primary btn-sm btn-block">Tiếp tục mua sắm</button></a>
                                </div>
                            </div>
                            @if (session('voucher'))
                                @php
                                    $voucher = session('voucher');
                                    $discountText = number_format($voucher['discount']) . ' VNĐ';
                                    if (isset($voucher['percent'])) {
                                        $discountText .= ' (' . $voucher['percent'] . '%)';
                                    }
                                @endphp
                                <div class="alert alert-success mt-3">
                                    Đã áp dụng mã: <strong>{{ $voucher['code'] }}</strong><br>
                                    Giảm giá: {{ $discountText }}

                                    <form action="{{ route('cart.remove-voucher') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-danger btn-sm rounded-pill shadow-sm px-3">
                                            <i class="bi bi-x-circle me-1"></i> Huỷ mã
                                        </button>

                                    </form>
                                </div>
                            @else
                                <form action="{{ route('cart.apply-voucher') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8 mb-3 mb-md-0">
                                            <input type="text" name="code" class="form-control py-3" id="coupon"
                                                placeholder="Coupon Code">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-primary btn-bg mt-2 w-100 shadow-sm rounded-2 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Áp dụng mã
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            @endif
                            <a href="{{ route('cart.voucher-list') }}"
                                class="btn btn-primary btn-sm mt-3 px-3 rounded-pill shadow-sm">
                                <i class="bi bi-tags-fill me-1"></i> Xem các mã giảm giá
                            </a>



                        </div>
                        <div class="col-md-6 pl-5">
                            <div class="row justify-content-end">
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-12 text-right border-bottom mb-5">
                                            <h3 class="text-black h4 text-uppercase">Tóm tắt đơn hàng</h3>
                                        </div>
                                    </div>
                                    @php
                                        $finalTotal = $totalPrice;
                                        $discount = session('voucher')['discount'] ?? 0;
                                        $finalTotal -= $discount;
                                    @endphp

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <span class="text-black">Tạm tính</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <strong class="text-black">{{ number_format($totalPrice, 0, ',', '.') }}
                                                VNĐ</strong>
                                        </div>
                                    </div>

                                    @if ($discount)
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span class="text-black">Giảm giá</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <strong
                                                    class="text-black text-success">-{{ number_format($discount, 0, ',', '.') }}
                                                    VNĐ</strong>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <span class="text-black">Tổng</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <strong class="text-black">{{ number_format($finalTotal, 0, ',', '.') }}
                                                VNĐ</strong>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('checkout') }}"
                                                class="btn btn-primary btn-lg py-3 btn-block">Thanh toán
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        @endif
    </div>
    @endsection
<style>
    button.btn-danger {
    background-color: #e74c3c;
    color: #fff;
    border: none;
}

button.btn-danger:hover {
    background-color: #c0392b;
}
</style>