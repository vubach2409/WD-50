@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="text-primary">Danh sách Đơn hàng</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request()->query('status') == 'all' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'all']) }}">
                        <i class="fas fa-list me-1"></i> Tất cả ({{ $allCount }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ !request()->query('status') || request()->query('status') == 'pending' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-clock me-1"></i> Chờ xử lý ({{ $pendingCount }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->query('status') == 'shipping' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'shipping']) }}">
                        <i class="fas fa-truck me-1"></i> Đang giao ({{ $shippingCount }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->query('status') == 'completed' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'completed']) }}">
                        <i class="fas fa-check-circle me-1"></i> Đã giao ({{ $completedCount }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->query('status') == 'cancelled' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">
                        <i class="fas fa-times-circle me-1"></i> Thất bại ({{ $cancelledCount }})
                    </a>
                </li>
            </ul>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã đơn hàng</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Mã giao dịch</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @if ($orders->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-1"></i> Không có đơn hàng nào với trạng thái này.
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $order->id }}</td>
                            <td class="align-middle">
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                            Chờ xử lý
                                        </option>
                                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>
                                            Đang giao
                                        </option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                            Đã giao
                                        </option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Thất bại
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td class="align-middle">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                            <td class="align-middle">{{ $order->payment_method }}</td>
                            <td class="align-middle">{{ $order->transaction_id }}</td>
                            <td class="align-middle">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            <td class="align-middle">
                                <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}"
                                   class="btn btn-success">Chi
                                    tiết</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
