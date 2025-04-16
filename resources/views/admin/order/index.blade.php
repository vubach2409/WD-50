@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Danh sách đơn hàng</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-success">
                {{ session('error') }}
            </div>
        @endif


        <div class="card">
            <div class="card-header">
                Tất cả đơn hàng
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
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
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>

                                <td>
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ
                                                Xử Lý
                                            </option>
                                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>
                                                Đang giao
                                            </option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                                Đã Giao
                                            </option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                Thất
                                                Bại
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                <td>{{ $order->payment_method }}</td>
                                <td>{{ $order->transaction_id }}</td>
                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                <td><a class="btn btn-success"
                                        href="{{ route('admin.orders.detail', ['id' => $order->id]) }}">Chi tiết</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Phân trang (nếu có) -->
                {{-- {{ $orders->links() }} --}}
            </div>
        </div>
    </div>
@endsection
