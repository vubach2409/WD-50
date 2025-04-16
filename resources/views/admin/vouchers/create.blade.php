@extends('layouts.admin')

@section('title', 'Thêm Voucher')

@section('content')
    <div class="container mt-4">
        <h2>Thêm Voucher Mới</h2>

        <form action="{{ route('admin.vouchers.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Mã Voucher</label>
                <input type="text" name="code" class="form-control" required value="{{ old('code') }}">
            </div>

            <div class="mb-3">
                <label>Loại</label>
                <select name="type" class="form-control">
                    <option value="fixed">Giảm tiền</option>
                    <option value="percent">Giảm phần trăm</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Giá trị</label>
                <input type="number" name="value" step="0.01" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Đơn hàng tối thiểu</label>
                <input type="number" name="min_order_amount" step="0.01" class="form-control">
            </div>

            <div class="mb-3">
                <label>Giới hạn sử dụng</label>
                <input type="number" name="usage_limit" class="form-control">
            </div>

            <div class="mb-3">
                <label>Bắt đầu</label>
                <input type="datetime-local" name="starts_at" class="form-control">
            </div>

            <div class="mb-3">
                <label>Hết hạn</label>
                <input type="datetime-local" name="expires_at" class="form-control">
            </div>
            <div class="mb-3 form-check">
                <!-- Input ẩn để gửi giá trị false (0) khi checkbox không được chọn -->
                <input type="hidden" name="is_active" value="0">

                <!-- Checkbox cho 'is_active' với giá trị mặc định là true (checked) -->
                <div class="mb-3 form-check">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input"
                        {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label">Kích hoạt</label>
                </div>


                <button class="btn btn-primary">Tạo Voucher</button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
