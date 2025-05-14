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

        <div class="mb-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'all' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'all']) }}">
                        Tất cả ({{ $orderCounts['all'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
                        Chờ xác nhận ({{ $orderCounts['pending'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'shipping' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'shipping']) }}">
                        Đang giao ({{ $orderCounts['shipping'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'completed']) }}">
                        Đã giao ({{ $orderCounts['completed'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}"
                       href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">
                        Đã hủy ({{ $orderCounts['cancelled'] }})
                    </a>
                </li>
            </ul>
        </div>

        @if ($orders->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> Bạn Chưa có đơn hàng nào.
            </div>
        @else
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
                        @foreach ($orders->sortByDesc('created_at') as $index => $order)
                            <tr>
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">{{ $order->id }}</td>
                                <td class="align-middle">
                                    @if ($order->status == 'pending' || $order->status == 'shipping')
                                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-control" onchange="this.form.submit()">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                    Chờ xử lý</option>
                                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>
                                                    Đang giao</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                                    Đã giao</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                    Đã hủy</option>
                                            </select>
                                        </form>
                                    @elseif ($order->status == 'completed')
                                        <span class="badge bg-success text-white">Đã giao</span>
                                    @elseif ($order->status == 'cancelled')
                                        <span class="badge bg-danger text-white">Đã hủy</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                                <td class="align-middle">{{ $order->payment_method }}</td>
                                <td class="align-middle">{{ $order->transaction_id }}</td>
                                <td class="align-middle">{{ $order->created_at->format('d-m-Y H:i') }}</td>
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

        {{--  <div class="d-flex justify-content-center mt-3">
            {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>  --}}
    </div>
@endsection