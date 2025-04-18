@extends('layouts.admin')

@section('title', 'Sửa Voucher')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary mb-4">Sửa Voucher: {{ $voucher->code }}</h2>

    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 20%;">Mã Voucher</th>
                        <td>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                placeholder="Nhập mã voucher" value="{{ old('code', $voucher->code) }}" autofocus>
                            @error('code') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Loại</th>
                        <td>
                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Giảm tiền</option>
                                <option value="percent" {{ old('type', $voucher->type) == 'percent' ? 'selected' : '' }}>Giảm phần trăm</option>
                            </select>
                            @error('type') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Giá trị</th>
                        <td>
                            <input type="number" name="value" step="0.01" class="form-control @error('value') is-invalid @enderror" 
                                placeholder="Nhập giá trị voucher" value="{{ old('value', $voucher->value) }}">
                            @error('value') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Đơn hàng tối thiểu</th>
                        <td>
                            <input type="number" name="min_order_amount" step="0.01" class="form-control @error('min_order_amount') is-invalid @enderror" 
                                placeholder="Nhập đơn hàng tối thiểu" value="{{ old('min_order_amount', $voucher->min_order_amount) }}">
                            @error('min_order_amount') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Giới hạn sử dụng</th>
                        <td>
                            <input type="number" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" 
                                placeholder="Nhập giới hạn sử dụng" value="{{ old('usage_limit', $voucher->usage_limit) }}">
                            @error('usage_limit') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Bắt đầu</th>
                        <td>
                            <input type="datetime-local" name="starts_at" class="form-control @error('starts_at') is-invalid @enderror" 
                                value="{{ old('starts_at', optional($voucher->starts_at)->format('Y-m-d\TH:i')) }}">
                            @error('starts_at') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Hết hạn</th>
                        <td>
                            <input type="datetime-local" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" 
                                value="{{ old('expires_at', optional($voucher->expires_at)->format('Y-m-d\TH:i')) }}">
                            @error('expires_at') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Kích hoạt</th>
                        <td class="text-end">
                            <select name="is_active" class="form-control">
                                <option value="1" {{ old('is_active', $voucher->is_active) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                                <option value="0" {{ old('is_active', $voucher->is_active) == 0 ? 'selected' : '' }}>Ngừng Kích hoạt</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Cập nhật Voucher</button>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
