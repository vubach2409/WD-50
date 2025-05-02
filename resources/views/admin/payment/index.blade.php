@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Danh Sách Thanh Toán</h2>

    <!-- Form lọc theo mã giao dịch -->
    <form action="{{ route('admin.orders.filter') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="transaction_id" class="form-control" placeholder="Nhập mã giao dịch..."
                value="{{ request('transaction_id') }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </div>
    </form>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($orders->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i> Chưa có đơn hàng nào.
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
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $order->id }}</td>
                            <td class="align-middle">{{ $order->payment->transaction_id }}</td>
                            <td class="align-middle">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                            <td class="align-middle">{{ $order->payment->payment_method }}</td>
                            <td class="align-middle">
                                <span
                                    class="badge {{ $order->payment->status == 'success' ? 'bg-success' : ($order->payment->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}"
                                    class="btn btn-success">Chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
