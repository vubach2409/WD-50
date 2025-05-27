@extends('layouts.user')

@section('title', 'Giỏ hàng')

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <div class="row mb-5">

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
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h2>Giỏ hàng của bạn</h2>

                @if ($cartItems && $cartItems->isEmpty())
                    <div class="alert alert-warning">Giỏ hàng của bạn đang trống!</div>
                @elseif ($cartItems && $cartItems->count())
                    <div class="site-blocks-table">
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
                                                <div style="display: flex; align-items: center; gap: 6px;">
                                                    <span
                                                        style="width:20px; height:20px; border-radius:50%; background-color:{{ $item->variant->color->code }}; border:1px solid #ccc;"></span>
                                                    <span> - {{ $item->variant->size->name ?? '' }}</span>
                                                </div>
                                            @else
                                                Không có biến thể
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->variant)
                                                {{ number_format($item->variant->price, 0, ',', '.') }} VNĐ
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->variant)
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" class="form-control quantity-input"
                                                        data-id="{{ $item->id }}"  data-original="{{ $item->quantity }}" value="{{ $item->quantity }}"
                                                        min="1" max="{{ $item->variant->stock }}" />
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="item-total">
                                            @if ($item->variant)
                                                {{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }}
                                                VNĐ
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Bạn có chắc muốn loại sản phẩm này?')">X</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Các nút và mã giảm giá -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-5">
                                <div class="col-md-6 mb-3">
                                    <form action="{{ route('cart.clear') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Bạn có muốn xóa giỏ hàng này không?')" class="btn btn-outline-black btn-sm btn-block">Xoá toàn
                                            bộ</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('products') }}" class="btn btn-outline-black btn-sm btn-block">
                                        ← Tiếp tục mua sắm
                                    </a>
                                </div>
                            </div>

                            @if (session('voucher'))
                                @php
                                    $voucher = session('voucher');
                                    $discountText = number_format($voucher['discount'], 0, ',', '.') . ' VNĐ';
                                    if (isset($voucher['percent'])) {
                                        $discountText .= ' (' . $voucher['percent'] . '%)';
                                    }
                                @endphp
                                <div class="alert alert-success mt-3">
                                    Đã áp dụng mã: <strong>{{ $voucher['code'] }}</strong><br>
                                    Giảm giá: {{ $discountText }}
                                    <form action="{{ route('cart.remove-voucher') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-outline-danger btn-sm rounded-pill shadow-sm px-3">
                                            <i class="bi bi-x-circle me-1"></i> Huỷ mã
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('cart.apply-voucher') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8 mb-3 mb-md-0">
                                            <input type="text" name="code" class="form-control py-3"
                                                placeholder="Coupon Code">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-dark btn-sm w-100 shadow-sm rounded-2 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Áp dụng mã
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <a href="{{ route('cart.voucher-list') }}"
                                class="btn btn-outline-primary btn-sm mt-3 px-3 rounded-pill shadow-sm">
                                <i class="bi bi-tags-fill me-1"></i> Xem các mã giảm giá
                            </a>
                        </div>

                        <!-- Tổng đơn hàng -->
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
                                        <div class="col-md-6"><span class="text-black">Tạm tính</span></div>
                                        <div class="col-md-6 text-right">
                                            <strong id="sub-total" class="text-black">
                                                {{ number_format($totalPrice, 0, ',', '.') }} VNĐ
                                            </strong>
                                        </div>
                                    </div>

                                    @if ($discount)
                                        <div class="row mb-3">
                                            <div class="col-md-6"><span class="text-black">Giảm giá</span></div>
                                            <div class="col-md-6 text-right"><strong
                                                    class="text-success">-{{ number_format($discount, 0, ',', '.') }}
                                                    VNĐ</strong></div>
                                        </div>
                                    @endif

                                    <!-- Tổng -->
                                    <div class="row mb-5">
                                        <div class="col-md-6"><span class="text-black">Tổng</span></div>
                                        <div class="col-md-6 text-right">
                                            <strong id="grand-total" class="text-black">
                                                {{ number_format($finalTotal, 0, ',', '.') }} VNĐ
                                            </strong>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('checkout') }}"
                                                class="btn btn-black btn-lg py-3 btn-block">Thanh toán</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->
                @endif
            </div>
        </div>
    </div>

    <!-- JS cập nhật số lượng -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
    $('.quantity-input').on('change', function() {
        const cartId = $(this).data('id');
        const quantity = $(this).val();
        const $row = $(this).closest('tr');

        $.ajax({
            url: '/cart/' + cartId,
            method: 'POST',
            data: {
                _method: 'PUT',
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                const newPriceFormatted = response.new_total_price.toLocaleString('vi-VN') + ' VNĐ';
                $row.find('.item-total').text(newPriceFormatted);

                $('#grand-total').text(response.grand_total.toLocaleString('vi-VN') + ' VNĐ');
                if (response.discount && response.discount > 0) {
                    $('.text-success').text('-' + response.discount.toLocaleString('vi-VN') + ' VNĐ');
                }
                $('#sub-total').text(response.sub_total.toLocaleString('vi-VN') + ' VNĐ');

                // ✅ Cập nhật lại mốc số lượng gốc
                $row.find('.quantity-input').data('original', quantity);

                toastr.success(response.success || 'Cập nhật thành công!');
            },
            error: function(xhr) {
                const originalQty = $row.find('.quantity-input').data('original');

                if (xhr.responseJSON?.errors?.quantity) {
                    toastr.error(xhr.responseJSON.errors.quantity[0]);
                } else if (xhr.responseJSON?.stock !== undefined) {
                    toastr.error('Số lượng vượt quá tồn kho. Còn lại: ' + xhr.responseJSON.stock);
                } else {
                    toastr.error('Đã xảy ra lỗi, vui lòng thử lại.');
                }

                // ✅ Reset lại số lượng về gốc
                $row.find('.quantity-input').val(originalQty);
            }
        });
    });
});

    </script>
@endsection
