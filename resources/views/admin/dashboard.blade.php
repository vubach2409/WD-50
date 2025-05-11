@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Bảng thống kê -->
    <div class="card shadow rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-chart-line"></i>
                Thống Kê Website Bán Hàng Nội Thất
            </h4>
        </div>
        
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">Thống Kê</th>
                        <th class="text-center">Số Lượng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Tổng Sản Phẩm</strong></td>
                        <td class="text-center"><span class="badge badge-success">{{ $totalProducts }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Tổng Đơn Hàng</strong></td>
                        <td class="text-center"><span class="badge badge-info">{{ $totalOrders }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Tổng Khách Hàng</strong></td>
                        <td class="text-center"><span class="badge badge-warning">{{ $totalCustomers }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Doanh Thu Tháng</strong></td>
                        <td class="text-center"><span class="badge badge-danger">{{ number_format($totalRevenue, 0, ',', '.') }} VND</span></td>
                    </tr>
                    <tr>
                        <td><strong>Sản Phẩm Mới Thêm</strong></td>
                        <td class="text-center"><span class="badge badge-primary">{{ $newProducts }}</span></td>
                    </tr>
                    {{-- 
                    <tr>
                        <td><strong>Sản Phẩm Bán Chạy Nhất</strong></td>
                        <td class="text-center">{{ $topSellingProduct->name ?? 'Chưa có dữ liệu' }}</td>
                    </tr>
                    --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
