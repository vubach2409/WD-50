@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">Giỏ Hàng Của Bạn</h2>
    
    @if($cartItems->isEmpty())
        <div class="alert alert-warning">Giỏ hàng của bạn đang trống!</div>
    @else
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="70">
                        </td>
                        <td>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control w-50 d-inline">
                                <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                            </form>
                        </td>
                        <td>{{ number_format($item->product->price, 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }} VNĐ</td>
                        <td>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end">
            <h4><strong>Tổng tiền: {{ number_format($totalPrice, 0, ',', '.') }} VNĐ</strong></h4>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ url('/') }}" class="btn btn-secondary">Tiếp tục mua sắm</a>

            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Xóa toàn bộ giỏ hàng</button>
            </form>

            <a href="{{ route('checkout') }}" class="btn btn-success">Thanh toán</a>
        </div>
    @endif
</div>
@endsection
