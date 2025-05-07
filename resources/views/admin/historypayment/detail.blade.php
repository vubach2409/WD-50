@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')
    <div class="container-fluid">
        <h2 class="text-primary mb-4">Đơn Hàng #{{ $order->id }}</h2>

        {{-- Thông tin khách hàng --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                Thông Tin Khách Hàng
            </div>
            <div class="card-body">
                <p><strong>Khách hàng:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Điện thoại:</strong> {{ $order->consignee_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->consignee_address }}, {{ $order->subdistrict }}, {{ $order->city }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                <p class="text-light"><strong class="text-secondary">Trạng thái:</strong>
                    <span class="badge 
                        {{ $order->status == 'Đã giao' ? 'bg-success' : ($order->status == 'Đang xử lý' ? 'bg-warning' : 'bg-info') }}">
                        {{ $order->status }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Sản phẩm --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                Sản Phẩm Trong Đơn Hàng
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="text-center">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="60" height="60" style="object-fit: cover; border-radius: 8px;">
                                </td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Thanh toán --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                Thông Tin Thanh Toán
            </div>
            <div class="card-body">
                @if ($order->payment)
                    <p><strong>Phương thức:</strong> {{ $order->payment->payment_method }}</p>

                    @if ($order->voucher_code && $order->discount_amount > 0)
                        <p><strong>Mã giảm giá ({{ $order->voucher_code }})</strong>: {{ number_format($order->discount_amount, 0, ',', '.') }}đ</p>
                    @endif

                    <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }}đ</p>
                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }}đ</p>
                    <p class="text-light"><strong class="text-secondary">Trạng thái:</strong>
                        <span class="badge {{ $order->payment->status == 'Success' ? 'bg-success' : 'bg-warning' }} ">
                            {{ $order->payment->status }}
                        </span>
                    </p>
                @else
                    <p class="text-danger">Chưa thanh toán</p>
                @endif
            </div>
        </div>

        {{-- Nút --}}
        <div class="text-center">
            <a href="{{ route('admin.payment.history') }}" class="btn btn-secondary mt-3">Quay lại</a>
            <a href="{{ route('admin.invoice.show', ['orderId' => $order->id]) }}"
                class="btn btn-success mt-3 text-decoration-none">Xem hoá đơn</a>
            <a href="{{ route('admin.invoice.download', ['orderId' => $order->id]) }}"
                class="btn btn-success text-decoration-none mt-3">In hoá đơn</a>
        </div>
    </div>
@endsection
