@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-box-open me-2"></i>
                Chi tiết đơn hàng #{{ $order->id ?? 'N/A' }}
            </h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">- Thông tin khách hàng</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Tên khách hàng:</strong> {{ $order->user->name ?? 'N/A' }}
                </li>
                <li class="list-group-item">
                    <strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}
                </li>
                <li class="list-group-item">
                    <strong>Số điện thoại:</strong> {{ $order->consignee_phone ?? 'N/A' }}
                </li>
                <li class="list-group-item">
                    <strong>Địa chỉ:</strong> {{ ($order->consignee_address ?? 'N/A') . ', ' . ($order->subdistrict ?? 'N/A') . ', ' . ($order->city ?? 'N/A') }}
                </li>
                 <li class="list-group-item">
                    <strong>Mã giao dịch:</strong> {{ $order->transaction_id ?? 'N/A' }}
                </li>
            </ul>

            <hr>

            <h5 class="card-title">- Chi tiết sản phẩm</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ảnh sản phẩm</th>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($order->items) && count($order->items) > 0)
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="text-center">
                                    @if(isset($item->product->image))
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name ?? 'N/A' }}" width="100">
                                    @else
                                        <span>Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity ?? 'N/A' }}</td>
                                <td>{{ number_format($item->price ?? 0, 0, ',', '.') }} VND</td>
                                <td>{{ number_format(($item->quantity ?? 0) * ($item->price ?? 0), 0, ',', '.') }} VND</td>
                            </tr>
                        @endforeach
                     @else
                        <tr><td colspan="5" class="text-center">Không có sản phẩm nào trong đơn hàng này.</td></tr>
                    @endif
                </tbody>
            </table>

            <hr>

            <h5 class="card-title">- Thông tin thanh toán</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Trạng thái:</strong> {{ ucfirst($order->payment->status ?? 'N/A') }}
                </li>
                <li class="list-group-item">
                    <strong>Vận chuyển:</strong> {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} VND
                </li>
                <li class="list-group-item">
                    <strong>Tổng tiền:</strong> {{ number_format($order->total ?? 0, 0, ',', '.') }} VND
                </li>
            </ul>

            <center>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách đơn hàng</a>
            </center>
        </div>
    </div>
</div>
@endsection
