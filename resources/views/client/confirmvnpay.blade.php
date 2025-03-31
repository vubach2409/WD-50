@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')
    <div class="untree_co-section">
        <div class="container">
            <div class="row">
                <form onsubmit="return confirm('Xác nhận đặt hàng')" action="{{ route('checkout.vnpay') }}" method="POST">
                    @csrf
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border bg-white">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="consignee_name" class="text-black">Name</label>
                                <input type="text" class="form-control" name="consignee_name" placeholder="Full name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="consignee_address" class="text-black">Address <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="consignee_address"
                                    placeholder="Street address">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <label for="consignee_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="consignee_phone" placeholder="Phone Number">
                        </div>
                        <label>Chọn phương thức vận chuyển:</label>
                        <select name="shipping_id" required class="form-control col-md-6">
                            @foreach ($shippings as $shipping)
                                <option value="{{ $shipping->id }}">
                                    {{ $shipping->method }} - {{ number_format($shipping->fee, 0, ',', '.') }} VNĐ
                                </option>
                            @endforeach
                        </select>


                        <div class="form-group">
                            <button type="submit" class="btn btn-info mt-3" name="redirect">Xác nhận</button>
                            <a href="{{ url()->previous() }}" class="btn btn-dark mt-3">Quay lại</a>
                        </div>
                </form>
            </div>
        </div>
    </div>

@endsection
