@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="mb-4">Chi tiết đơn hàng #{{ $order->id }}</h1>

        <div class="card">
            <div class="card-header">
                Thông tin đơn hàng
            </div>
            <div class="card-body">
                <h5 class="card-title">Thông tin khách hàng</h5>
                <p><strong>Tên khách hàng:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->consignee_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->consignee_address }}, {{ $order->subdistrict }}, {{ $order->city }}
                </p>
                <p><strong>Mã giao dịch:</strong> {{ $order->transaction_id }}</p>

                <hr>

                <h5 class="card-title">Chi tiết sản phẩm</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                                <td>{{ number_format($item->quantity * $item->price, 0, ',', '.') }} VND</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <h5 class="card-title">Thông tin thanh toán</h5>
                <p><strong>Trạng thái:</strong> {{ ucfirst($order->payment->status) }}</p>
                <p><strong>Vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }} VND</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }} VND</p>

                <a href="{{ route('admin.orders.show') }}" class="btn btn-primary">Quay lại danh sách đơn hàng</a>
            </div>
        </div>
    </div>
@endsection
