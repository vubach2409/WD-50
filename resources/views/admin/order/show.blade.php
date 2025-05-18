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
                    <li class="list-group-item">
                        <strong>Trạng thái đơn hàng:</strong>
                        <span
                            class="badge {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'shipping' ? 'bg-info' : ($order->status == 'completed' ? 'bg-success' : 'bg-danger')) }}">
                            {{ app('App\Http\Controllers\Admin\OrderController')->getOrderStatusName($order->status) }}
                        </span>
                    </li>
                </ul>

                <hr>

                <h5 class="card-title">- Chi tiết sản phẩm</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
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
                                    <td class="">
                                        @if ($item->variant_image)
                                            <img src="{{ asset('storage/' . $item->variant_image) }}"
                                                alt="{{ $item->product_name }}" width="100">
                                        @else
                                            <span>Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $item->product_name }}</td>
                                    <td class="align-middle">
                                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                                            <span style="
                                                display: inline-block;
                                                width: 20px;
                                                height: 20px;
                                                background-color: {{ $item->color_code }};
                                                border: 1px solid #ccc;
                                                border-radius: 4px;
                                            "></span>
                                            <span>- {{ $item->size_name ?? 'Không size' }}</span>
                                        </span>
                                                                            
                                    </td>
                                    <td class="align-middle">{{ $item->variant_sku ?? 'Không có' }}</td>
                                    <td class="align-middle">{{ $item->quantity }}</td>
                                    <td class="align-middle">{{ number_format($item->price, 0, ',', '.') }} VND</td>
                                    <td class="align-middle">{{ number_format($item->quantity * $item->price, 0, ',', '.') }} VND</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>
                <!-- Thông tin thanh toán -->
                <h5 class="card-title">- Thông tin thanh toán</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Trạng thái thanh toán:</strong>
                        @if ($order->payment->refund_status === 'refunded')
                            <span class="badge bg-success">Đã hoàn tiền</span>
                        @elseif ($order->payment->status === 'success')
                            <span class="badge bg-success">Thành công</span>
                        @elseif ($order->payment->status === 'pending')
                            <span class="badge bg-warning">Chưa thanh toán</span>
                        @elseif ($order->payment->status === 'failed')
                            <span class="badge bg-warning">Thất bại</span>
                        @elseif ($order->payment->status === 'cancelled_pending_refund')
                            <span class="badge bg-warning">Đang chờ hoàn tiền</span>
                        @else
                            <span class="badge bg-danger">Thất bại</span>
                        @endif
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
                    <a href="{{ route('admin.orders.show') }}" class="btn btn-secondary mt-3">Quay lại danh sách
                        đơn
                        hàng</a>
                </center>
            </div>
        </div>
    </div>
@endsection