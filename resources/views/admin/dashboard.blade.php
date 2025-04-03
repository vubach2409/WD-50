@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Thống Kê Website Bán Hàng Nội Thất</h1>

    <!-- Bảng thống kê -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Thống Kê Chi Tiết</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Thống Kê</th>
                        <th>Số Lượng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Tổng Sản Phẩm</strong></td>
                        <td><span class="btn btn-success">{{ $totalProducts }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Tổng Đơn Hàng</strong></td>
                        <td><span class="btn btn-info">{{ $totalOrders }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Tổng Khách Hàng</strong></td>
                        <td><span class="btn btn-warning text-dark">{{ $totalCustomers }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Doanh Thu Tháng</strong></td>
                        <td><span class="btn btn-danger">{{ number_format($totalRevenue, 0, ',', '.') }} VND</span></td>
                    </tr>
                    <tr>
                        <td><strong>Sản Phẩm Mới Thêm</strong></td>
                        <td><span class="btn btn-primary">{{ $newProducts }}</span></td>
                    </tr>
                    {{-- 
                    <tr>
                        <td><strong>Sản Phẩm Bán Chạy Nhất</strong></td>
                        <td>{{ $topSellingProduct->name ?? 'Chưa có dữ liệu' }}</td>
                    </tr>
                    --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection
