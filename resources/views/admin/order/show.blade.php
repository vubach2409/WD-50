@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-3">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-box-open me-2"></i>
                    Chi tiết đơn hàng #{{ $order->id }}
                </h4>
            </div>
            <div class="card-body">
                <!-- Thông tin khách hàng -->
                <h5 class="card-title">- Thông tin khách hàng</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Tên khách hàng:</strong> {{ $order->user->name }}
                    </li>
                    <li class="list-group-item">
                        <strong>Email:</strong> {{ $order->user->email }}
                    </li>
                    <li class="list-group-item">
                        <strong>Số điện thoại:</strong> {{ $order->consignee_phone }}
                    </li>
                    <li class="list-group-item">
                        <strong>Địa chỉ:</strong> {{ $order->consignee_address }}, {{ $order->subdistrict }},
                        {{ $order->city }}
                    </li>
                    <li class="list-group-item">
                        <strong>Mã giao dịch:</strong> {{ $order->transaction_id }}
                    </li>
                </ul>

                <hr>

                <!-- Chi tiết sản phẩm -->
                <h5 class="card-title">- Chi tiết sản phẩm</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Biến thể</th>
                            <th>SKU</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="text-center">
                                    @if ($item->variant_image)
                                        <img src="{{ asset('storage/' . $item->variant_image) }}"
                                            alt="{{ $item->product_name }}" width="100">
                                    @else
                                        <span>Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $item->product_name }}</td>
                                <td>
                                    {{ $item->color_name ?? 'Không màu' }} / {{ $item->size_name ?? 'Không size' }}
                                </td>
                                <td>{{ $item->variant_sku ?? 'Không có' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                                <td>{{ number_format($item->quantity * $item->price, 0, ',', '.') }} VND</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <!-- Thông tin thanh toán -->
                <h5 class="card-title">- Thông tin thanh toán</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Trạng thái:</strong> {{ ucfirst($order->payment->status) }}
                    </li>
                    <li class="list-group-item">
                        <strong>Vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }} VND
                    </li>
                    @if ($order->voucher_code && $order->discount_amount > 0)
                        <li class="list-group-item">
                            <strong>Mã giảm giá:</strong>({{ $order->voucher_code }}):
                            -{{ number_format($order->discount_amount, 0, ',', '.') }} VND
                        </li>
                    @endif

                    <li class="list-group-item">
                        <strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }} VND
                    </li>
                </ul>

                <center>
                    <a href="{{ route('admin.orders.show') }}" class="btn btn-secondary mt-3">Quay lại danh sách đơn
                        hàng</a>
                </center>
            </div>
        </div>
    </div>
@endsection