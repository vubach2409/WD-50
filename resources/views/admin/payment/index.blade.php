@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="text-danger">Danh Sách Đơn Hàng Bị Huỷ</h2>

        <form action="{{ route('admin.orders.cancelled') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="transaction_id" class="form-control" placeholder="Nhập mã giao dịch..."
                    value="{{ request('transaction_id') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-danger">Tìm kiếm</button>
                </div>
            </div>
        </form>

        @if ($orders->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> Không có đơn hàng bị huỷ.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã đơn hàng</th>
                            <th>Mã giao dịch</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái thanh toán</th>
                            <th>Trạng thái hoàn tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $index => $order)
                            <tr>
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">{{ $order->id }}</td>
                                <td class="align-middle">{{ $order->payment->transaction_id ?? '-' }}</td>
                                <td class="align-middle">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                                <td class="align-middle">{{ $order->payment->payment_method ?? '-' }}</td>
                                <td class="align-middle">
                                    @php
                                        $status = $order->payment->status ?? 'N/A';

                                        $badgeClass = match ($status) {
                                            'success' => 'bg-success',
                                            'pending' => 'bg-warning',
                                            'cancelled_pending_refund' => 'bg-info',
                                            'refunded_successfully' => 'bg-success',
                                            default => 'bg-danger',
                                        };

                                        $statusText = match ($status) {
                                            'success' => 'Đã thanh toán',
                                            'pending' => 'Đang xử lý',
                                            'cancelled_pending_refund' => 'Đang chờ hoàn tiền',
                                            'refunded_successfully' => 'Đã hoàn tiền',
                                            default => 'Thanh toán thất bại',
                                        };
                                    @endphp

                                    <span class="badge {{ $badgeClass }}">
                                        {{ $statusText }}
                                    </span>

                                </td>
                                <td class="align-middle">
                                    @if ($order->payment->refund_status === 'refunded')
                                        <span class="badge bg-success">Đã hoàn tiền</span>
                                    @elseif ($order->payment->refund_status === 'processing')
                                        <span class="badge bg-warning">Đang xử lý</span>
                                    @else
                                        <span class="badge bg-light text-dark">Chưa hoàn</span>
                                    @endif
                                </td>


                                <td class="align-middle">
                                    <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}"
                                        class="btn btn-secondary btn-sm mb-1">Chi tiết</a>

                                    @if ($order->payment->status === 'cancelled_pending_refund' && $order->payment->refund_status !== 'refunded')
                                        <form action="{{ route('admin.orders.refund', ['id' => $order->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn hoàn tiền đơn hàng này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Hoàn tiền</button>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
