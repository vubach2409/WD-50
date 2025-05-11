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
                        <div class="row">
                            <!-- Tổng sản phẩm -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Total Products</h5>
                                        <p>{{ number_format($totalProducts) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tổng đơn hàng -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Total Orders</h5>
                                        <p>{{ number_format($totalOrders) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tổng doanh thu -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Total Revenue</h5>
                                        <p>{{ number_format($totalRevenue, 0, ',', '.') }} ₫</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Sản phẩm mới trong tháng -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>New Products</h5>
                                        <p>{{ number_format($newProducts) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>


            </div>
            </thead>
            <tbody>
                <!-- Biểu đồ doanh thu theo ngày -->
                <h3>Doanh thu theo ngày</h3>
                <canvas id="revenueChart" height="150"></canvas>
                </table>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line', // Biểu đồ đường
            data: {
                labels: @json($labels), // Các nhãn là ngày
                datasets: [{
                    label: 'Doanh thu theo ngày (VNĐ)',
                    data: @json($revenues), // Dữ liệu doanh thu theo từng ngày
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Màu nền dưới đường
                    borderColor: 'rgba(75, 192, 192, 1)', // Màu đường
                    borderWidth: 2, // Độ dày của đường
                    fill: true, // Tô màu phía dưới đường
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' ₫'; // Hiển thị doanh thu với dấu phẩy
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
