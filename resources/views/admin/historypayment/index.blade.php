@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="text-primary">Lịch Sử Giao Dịch</h2>

        @if ($orders->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> Chưa có giao dịch nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Mã giao dịch</th>
                            <th>Phương Thức Thanh Toán</th>
                            <th>Trạng Thái Thanh Toán</th>
                            <th>Chi Tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>ph{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                <td><span class="badge bg-success">{{ $order->payment->transaction_id }}</span></td>
                                <td>{{ $order->payment->payment_method }}</td>
                                <td>
                                    @if ($order->payment)
                                        <span
                                            class="badge {{ $order->payment->status == 'success' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $order->payment->status }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.history.detail', $order->id) }}" class="btn btn-dark">Xem</a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
