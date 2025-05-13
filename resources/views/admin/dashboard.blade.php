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
                                        <h5>Tổng sản phẩm</h5>
                                        <p>{{ number_format($totalProducts) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tổng đơn hàng -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Tổng đơn hàng</h5>
                                        <p>{{ number_format($totalOrders) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tổng doanh thu -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Tổng doanh thu</h5>
                                        <p>{{ number_format($totalRevenue, 0, ',', '.') }} ₫</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Sản phẩm mới trong tháng -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Sản phẩm mới </h5>
                                        <p>{{ number_format($newProducts) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Biểu đồ doanh thu theo ngày -->
                            <h3>Doanh thu theo ngày</h3>
                            <canvas id="revenueChart" height="150"></canvas>
                        </div>
                    </thead>

                </table>
                <div class="mt-5">
                    <h3 class="mb-4">Top 10 sản phẩm bán chạy</h3>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topProducts as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->total_sold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <canvas id="bestSellingChart" height="100"></canvas>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Doanh thu theo ngày (VNĐ)',
                        data: @json($revenues),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' ₫';
                                }
                            }
                        }
                    }
                }
            });
        </script>

        <script>
            const bestSellingCtx = document.getElementById('bestSellingChart').getContext('2d');
            const bestSellingChart = new Chart(bestSellingCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topProducts->pluck('product_name')) !!},
                    datasets: [{
                        label: 'Số lượng bán',
                        data: {!! json_encode($topProducts->pluck('total_sold')) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        </script>
    @endsection
