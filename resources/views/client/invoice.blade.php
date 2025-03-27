<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #{{ $order->id }}</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font hỗ trợ Tiếng Việt --}}
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('https://cdn.jsdelivr.net/gh/ogibayashi/fonts/DejaVuSans.ttf') format('truetype');
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            background-color: #f8f9fa;
        }

        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <h2>HÓA ĐƠN THANH TOÁN</h2>
        <hr>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Tên khách hàng:</strong> {{ $order->consignee_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->consignee_phone }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Địa chỉ:</strong> {{ $order->consignee_address }}</p>
                <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Tổng tiền: {{ number_format($order->total, 0, ',', '.') }}đ</p>
        <p><strong>Phương thức thanh toán:</strong> VNPay</p>
        <p><strong>Trạng thái:</strong> Thành công</p>

        @if (!isset($isPDF) || !$isPDF)
            <div class="text-center mt-3">
                <a href="{{ route('invoice.download', $order->id) }}" class="btn btn-primary">Tải Hóa Đơn PDF</a>
            </div>
        @endif

    </div>

</body>

</html>

{{-- //barryvdh/laravel-dompdf:  thư viện tải hoá đơn --}}
