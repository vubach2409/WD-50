@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">Thanh Toán</h2>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ nhận hàng</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Phương thức thanh toán</label>
            <select name="payment_method" class="form-control" required>
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="bank">Chuyển khoản ngân hàng</option>
            </select>
        </div>

        <h4><strong>Tổng tiền: {{ number_format($totalPrice, 0, ',', '.') }} VNĐ</strong></h4>

        <button type="submit" class="btn btn-success mt-3">Xác nhận đặt hàng</button>
    </form>
</div>
@endsection
