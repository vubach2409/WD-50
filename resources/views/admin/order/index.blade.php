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
                            <th>Trạng thái</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Mã giao dịch</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $index => $order)
                            <tr>
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">{{ $order->id }}</td>
                                <td class="align-middle">
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ
                                                xử lý</option>
                                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>
                                                Đang giao</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                                Đã giao</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                Thất bại</option>
                                        </select>
                                    </form>
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

        {{-- <div class="d-flex justify-content-center mt-3">
            {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div> --}}
    </div>
@endsection
