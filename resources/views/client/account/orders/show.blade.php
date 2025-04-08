@extends('layouts.user')

@section('title', 'Order Details #' . $order->id)

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
                                <p><strong>Trạng thái đơn hàng:</strong> {{ ucfirst($order->status) }}</p>

                                @if ($order->payment)
                                    <p><strong>Trạng thái thanh toán:</strong>
                                        <span
                                            class="badge bg-{{ $order->payment->status === 'completed' ? 'success' : ($order->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->payment->status) }}
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
                                                    <img src="{{ asset('storage/' . $item->variant->image) }}"
                                                        class="img-thumbnail me-3"
                                                        style="width: 60px; height: 60px; object-fit: cover;">

                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                        @if ($item->variant)
                                                            <small class="text-muted">Biến thể:
                                                                {{ $item->variant->variation_name }}
                                                                @if ($item->variant->color)
                                                                    - {{ $item->variant->color->name }}
                                                                @endif
                                                                @if ($item->variant->size)
                                                                    - {{ $item->variant->size->name }}
                                                                @endif
                                                            </small><br>
                                                        @endif
                                                        <small class="text-muted">SKU:
                                                            {{ $item->variant->sku ?? '.' }}</small>
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
