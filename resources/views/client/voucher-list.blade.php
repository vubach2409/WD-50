@extends('layouts.user')

@section('title', 'Mã Giảm Giá')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Mã giảm giá đang áp dụng</h2>

        @if ($vouchers->isEmpty())
            <div class="alert alert-info">Hiện không có mã giảm giá nào khả dụng.</div>
        @else
            <div class="row">
                @foreach ($vouchers as $voucher)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <h5 class="card-title">{{ $voucher->code }}</h5>
                                <p class="card-text">
                                    {!! $voucher->type === 'percent'
                                        ? 'Giảm ' . $voucher->value . '%'
                                        : 'Giảm ' . number_format($voucher->value, 0, ',', '.') . ' VNĐ' !!}
                                </p>
                                @if ($voucher->min_order_amount)
                                    <p class="text-muted">
                                        Áp dụng cho đơn từ {{ number_format($voucher->min_order_amount, 0, ',', '.') }} VNĐ
                                    </p>
                                @endif
                                @if ($voucher->expires_at)
                                    <p class="text-muted mb-0">
                                        Hết hạn: {{ $voucher->expires_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection