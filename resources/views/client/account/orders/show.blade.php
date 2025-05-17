@extends('layouts.user')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
    <div class="container py-5 mt-5 mb-5">
        <div class="row">
            <div class="col-lg-3">
                @include('client.account.partials.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="card-title mb-0">Đơn hàng #{{ $order->id }}</h2>
                            <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại đơn hàng
                            </a>
                        </div>

                        {{-- Thông tin đơn hàng và vận chuyển --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Thông tin đơn hàng</h5>
                                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                                <p><strong>Trạng thái đơn hàng:</strong> {{ $order->translated_status }}</p>

                                @if ($order->payment)
                                    <p><strong>Trạng thái thanh toán:</strong>
                                        @php
                                            $status = $order->payment->status;
                                            $isVNPay = $order->payment->payment_method === 'vnpay';
                                            $badgeClass = match ($status) {
                                                'completed' => 'success',
                                                'pending' => 'warning',
                                                'failed' => $isVNPay ? 'warning' : 'danger',
                                                default => 'secondary',
                                            };
                                            $statusText =
                                                $isVNPay && $status === 'failed'
                                                    ? 'Đã huỷ - Chờ hoàn tiền VNPAY'
                                                    : $order->translated_payment_status;
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </p>
                                    <p><strong>Phương thức thanh toán:</strong>
                                        {{ ucfirst($order->payment->payment_method) }}</p>
                                    @if ($order->payment->transaction_id)
                                        <p><strong>Mã giao dịch:</strong> {{ $order->payment->transaction_id }}</p>
                                    @endif
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3">Thông tin vận chuyển</h5>
                                <p><strong>Khách hàng:</strong> {{ $order->consignee_name }}</p>
                                <p><strong>Email:</strong> {{ $order->email }}</p>
                                <p><strong>SĐT:</strong> {{ $order->consignee_phone }}</p>
                                <p><strong>Địa chỉ:</strong> {{ $order->consignee_address }}, {{ $order->subdistrict }},
                                    {{ $order->city }}</p>
                                <p><strong>Phương thức vận chuyển:</strong> {{ $order->ship->method }}</p>
                            </div>
                        </div>

                        {{-- Bảng sản phẩm --}}
                        <div class="table-responsive mb-4">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tạm tính</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal = 0;
                                    @endphp

                                    @foreach ($order->items as $item)
                                        @php
                                            $lineTotal = $item->price * $item->quantity;
                                            $subtotal += $lineTotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->variant_image)
                                                        <img src="{{ asset('storage/' . $item->variant_image) }}"
                                                            class="img-thumbnail me-3"
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('images/no-image.png') }}"
                                                            class="img-thumbnail me-3"
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                    @endif

                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                        <small class="text-muted">
                                                            Biến thể:
                                                            {{ $item->variant_name ?? 'Không có' }}
                                                            @if ($item->color_name)
                                                                - {{ $item->color_name }}
                                                            @endif
                                                            @if ($item->size_name)
                                                                - {{ $item->size_name }}
                                                            @endif
                                                        </small><br>
                                                        <small class="text-muted">SKU:
                                                            {{ $item->variant_sku ?? '.' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($lineTotal, 0, ',', '.') }} VNĐ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tạm tính:</strong></td>
                                        <td>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    @if ($order->voucher_code && $order->discount_amount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Mã giảm giá
                                                    ({{ $order->voucher_code }}):</strong></td>
                                            <td>-{{ number_format($order->discount_amount, 0, ',', '.') }} VNĐ</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                        <td>{{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                        <td>{{ number_format($order->total, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection