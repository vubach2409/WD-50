@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')
    <div class="untree_co-section">
        <div class="container mt-4">
            <div class="card shadow-sm p-4">
                <h2 class="text-center">Thông Tin Cá Nhân</h2>

                <!-- Hiển thị thông báo thành công -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="text-center mb-3">
                    @php
                        $avatarPath = $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png');
                    @endphp
                    <img src="{{ $avatarPath }}" alt="Avatar" class="rounded-circle border border-3" width="150">
                </div>

                <form method="POST" action="{{ route('userclient.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Họ Tên</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số Điện Thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thay đổi mật khẩu (Để trống nếu không đổi)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" name="avatar" class="form-control">
                        @if ($user->avatar)
                            <p class="mt-2">Ảnh hiện tại:</p>
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar cũ" class="rounded"
                                width="100">
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                </form>

                <!-- Nút mở modal -->
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary btn-lg" data-bs-toggle="modal"
                        data-bs-target="#orderModal">
                        Xem Đơn Hàng
                    </button>
                    <button type="button" class="btn btn-outline-success btn-lg" data-bs-toggle="modal"
                        data-bs-target="#transactionModal">
                        Lịch Sử Giao Dịch
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Đơn Hàng -->
        <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderModalLabel">Danh Sách Đơn Hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($orders->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã Đơn</th>
                                        <th>Ngày Đặt</th>
                                        <th>Tổng Tiền</th>
                                        <th>Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                            <td>{{ number_format($order->total, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $order->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center">Bạn chưa có đơn hàng nào.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Lịch Sử Giao Dịch -->
        <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionModalLabel">Lịch Sử Giao Dịch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($transactions->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã giao dịch</th>
                                        <th>Số tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian</th>
                                        <th>Hoá đơn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>#{{ $transaction->transaction_id }}</td>
                                            <td>{{ number_format($transaction->amount, 0, ',', '.') }} VND</td>
                                            <td>
                                                @if ($transaction->status == 'success')
                                                    <span class="badge bg-success">Thành công</span>
                                                @else
                                                    <span class="badge bg-danger">Thất bại</span>
                                                @endif
                                            </td>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td><a class="badge bg-danger text-decoration-none"
                                                    href="{{ route('invoice.show', $transaction->order_id) }}"
                                                    target="_blank">Xem hóa đơn</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center">Chưa có giao dịch nào.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
