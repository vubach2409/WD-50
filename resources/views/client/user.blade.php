@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')
    <div class="untree_co-section">
        <div class="container">
            <h2>Lịch sử giao dịch</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã giao dịch</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_id }}</td>
                            <td>{{ number_format($transaction->amount, 0, ',', '.') }} VND</td>
                            <td>
                                @if ($transaction->status == 'success')
                                    <span class="badge bg-success">Thành công</span>
                                @else
                                    <span class="badge bg-danger">Thất bại</span>
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
