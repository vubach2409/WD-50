@extends('layouts.admin')

@section('title', 'Sửa Voucher')

@section('content')
    <div class="container mt-4">
        <h2>Sửa Voucher: {{ $voucher->code }}</h2>

        <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Mã Voucher</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}" required>
            </div>

            <div class="mb-3">
                <label>Loại</label>
                <select name="type" class="form-control">
                    <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>Giảm tiền</option>
                    <option value="percent" {{ $voucher->type == 'percent' ? 'selected' : '' }}>Giảm phần trăm</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Giá trị</label>
                <input type="number" name="value" step="0.01" class="form-control" value="{{ $voucher->value }}">
            </div>

            <div class="mb-3">
                <label>Đơn hàng tối thiểu</label>
                <input type="number" name="min_order_amount" step="0.01" class="form-control"
                    value="{{ $voucher->min_order_amount }}">
            </div>

            <div class="mb-3">
                <label>Giới hạn sử dụng</label>
                <input type="number" name="usage_limit" class="form-control" value="{{ $voucher->usage_limit }}">
            </div>

            <div class="mb-3">
                <label>Bắt đầu</label>
                <input type="datetime-local" name="starts_at" class="form-control"
                    value="{{ optional($voucher->starts_at)->format('Y-m-d\TH:i') }}">
            </div>

            <div class="mb-3">
                <label>Hết hạn</label>
                <input type="datetime-local" name="expires_at" class="form-control"
                    value="{{ optional($voucher->expires_at)->format('Y-m-d\TH:i') }}">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" value="1" class="form-check-input"
                    {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                <label class="form-check-label">Kích hoạt</label>
            </div>
            <button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
