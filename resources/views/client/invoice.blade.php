<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #{{ $order->id }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('https://cdn.jsdelivr.net/gh/ogibayashi/fonts/DejaVuSans.ttf') format('truetype');
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            font-family: "DejaVu Sans", sans-serif;
        }

        h5 {
            font-style: oblique;
            font-size: 30px;
            margin-bottom: 30px;
        }

        .invoice-container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
        }

        .hoadon {
            text-align: right;

            p {
                padding-top: 5px;
                font-size: 18px;
            }

            span {
                font-size: 15px;
            }
        }

        .congty {
            text-align: left;

            p {
                font-weight: normal;
                padding-top: 5px;
                font-size: 18px;
            }
        }

        .invoice-header {
            text-align: center;
            font-weight: bold;
            font-size: 24px;
        }

        .invoice-table th {
            background-color: #303841;
            color: #fff;
            text-align: center;
        }

        .invoice-table td {
            text-align: center;
        }

        .total-section {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        h2 {
            color: green;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header row">
            <div class="col congty">
                <h2>Nội thất PoLy</h2>
                <p><Strong>Địa chỉ:</Strong> 139 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội</p>
                <p><Strong>Điện thoại:</Strong> 0353174409</p>
            </div>
            <div class="col text-end hoadon">
                <h2>Hóa Đơn Thanh Toán</h2>
                <p>Mã đơn hàng:#{{ $order->id }}</p>
                <span>Ngày đặt:{{ $order->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        @php
            $paymentMethods = [
                'cod' => 'Thanh toán khi nhận hàng',
                'vnpay' => 'Thẻ tín dụng',
            ];
        @endphp
        <h5>Thông tin khách hàng: </h5>

        <div class="row">
            <p><strong>Tên Khách hàng:</strong> {{ $order->consignee_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Điện thoại:</strong> {{ $order->consignee_phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->consignee_address }}, {{ $order->subdistrict }},
                {{ $order->city }}</p>
            <p><strong>Phương thức thanh toán:</strong> {{ $paymentMethods[$order->payment_method] }}</p>
        </div>

        <h5>Thông tin đơn hàng:</h5>
        <table class="table table-bordered invoice-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="total-section">Tổng phụ:
            {{ number_format($subtotal, 0, ',', '.') }}đ</p>
        <p class="total-section">Vận chuyển & Xử lý: {{ number_format($order->shipping_fee, 0, ',', '.') }}đ</p>
        <p class="total-section">Tổng: {{ number_format($order->total, 0, ',', '.') }}đ</p>

        <a href="javascript:history.back()" class="btn btn-secondary">Quay lại</a>
        {{-- <a href="{{ route('admin.invoice.download', ['orderId' => $order->id]) }}"
            class="btn btn-success text-decoration-none">In
            hoá
            đơn</a> --}}
    </div>
</body>

</html>
