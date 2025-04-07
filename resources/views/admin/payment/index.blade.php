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
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if ($orders->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> Chưa có đơn hàng nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Mã giao dịch</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Phương Thức Thanh Toán</th>
                            <th>Trạng Thái Thanh Toán</th>
                            <th>Cập Nhật Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->payment->transaction_id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                <td>{{ $order->payment->payment_method }}</td>
                                <td>
                                    <span
                                        class="badge {{ $order->payment->status == 'success' ? 'bg-success' : ($order->payment->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.update.payment.status', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <option value="pending"
                                                {{ $order->payment->status == 'pending' ? 'selected' : '' }}>Chờ Xử Lý
                                            </option>
                                            <option value="success"
                                                {{ $order->payment->status == 'success' ? 'selected' : '' }}>Đã Thanh Toán
                                            </option>
                                            <option value="failed"
                                                {{ $order->payment->status == 'failed' ? 'selected' : '' }}>Thất Bại
                                            </option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
