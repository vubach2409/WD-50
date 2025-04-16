@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')
    <div class="container">
        <h2>Chi Tiết Đơn Hàng #{{ $order->id }}</h2>

        <p><strong>Khách hàng:</strong> {{ $order->user->name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
        <p><strong>Điện thoại:</strong> {{ $order->consignee_phone }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->consignee_address }}, {{ $order->subdistrict }},
            {{ $order->city }}</p>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
        <p><strong>Trạng thái:</strong> <span class="badge bg-info">{{ $order->status }}</span></p>


        <h3>Sản phẩm trong đơn hàng:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Thông tin thanh toán:</h3>
        @if ($order->payment)
            <p><strong>Phương thức:</strong> {{ $order->payment->payment_method }}</p>
            @if ($order->voucher_code && $order->discount_amount > 0)
                <p colspan="3" class="text-end"><strong>Mã giảm giá
                        ({{ $order->voucher_code }})</strong>: -{{ number_format($order->discount_amount, 0, ',', '.') }}
                    VNĐ</p>
            @endif

            <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }}đ</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }}đ</p>
            <p><strong>Trạng thái:</strong> <span
                    class="badge {{ $order->payment->status == 'Success' ? 'bg-success' : 'bg-warning' }}">
                    {{ $order->payment->status }}
                </span></p>
        @else
            <p class="text-danger">Chưa thanh toán</p>
        @endif

        <a href="{{ route('admin.payment.history') }}" class="btn btn-secondary mt-3">Quay lại</a>

        <a href="{{ route('admin.invoice.show', ['orderId' => $order->id]) }}"
            class="btn btn-success mt-3 text-decoration-none">Xem
            hoá
            đơn</a>
        <a href="{{ route('admin.invoice.download', ['orderId' => $order->id]) }}"
            class="btn btn-success text-decoration-none mt-3">In
            hoá
            đơn</a>
    </div>
@endsection
