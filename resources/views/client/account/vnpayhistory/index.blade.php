@extends('layouts.user')

@section('title', 'Vnpay History')

@section('content')
    <div class="container py-5 mt-5 mb-5">
        <div class="row">
            <div class="col-lg-3">
                @include('client.account.partials.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Lịch sử giao dịch</h2>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Loại Giao Dịch</th>
                                    <th>Số Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th>Hoá đơn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $transaction->payment_method }}</td>
                                        <td>{{ number_format($transaction->amount, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ ucfirst($transaction->status) }}</td>
                                        <td><a href="{{ route('invoice.show', ['orderId' => $transaction->order_id]) }}"
                                                class="badge bg-success text-decoration-none">Xem
                                                hoá
                                                đơn</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
