@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bảng điều khiển</h1>
    </div>

    <!-- Content Row - Cards -->
    <div class="row">

         <!-- Tổng Sản Phẩm Card -->
         <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Tổng Sản Phẩm</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tổng Đơn Hàng Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.orders.show') }}" class="text-decoration-none">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Tổng Đơn Hàng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tổng Khách Hàng Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tổng Khách Hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh Thu Tháng Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Doanh Thu (Tháng này)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRevenueThisMonth ?? 0, 0, ',', '.') }} VND</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Charts -->
    <div class="row">
        <!-- Area Chart: Doanh thu 7 ngày gần nhất -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Doanh Thu 7 Ngày Gần Nhất</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart: Tỷ lệ trạng thái đơn hàng -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tỷ Lệ Trạng Thái Đơn Hàng</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="height: 320px;">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
{{-- Nhúng Chart.js qua CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Dữ liệu từ PHP (đảm bảo các biến này được truyền từ controller)
        const revenueLabels = @json($revenueLast7DaysLabels ?? []);
        const revenueData = @json($revenueLast7DaysData ?? []);
        const orderStatusLabels = @json($orderStatusLabels ?? []);
        const orderStatusData = @json($orderStatusData ?? []);

        // Biểu đồ Doanh thu 7 ngày gần nhất
        if (document.getElementById('revenueChart') && revenueLabels.length > 0) {
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line', // Có thể đổi thành 'bar'
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: revenueData,
                        borderColor: 'rgba(78, 115, 223, 1)',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)', // Màu nền dưới đường line
                        tension: 0.3, // Độ cong của đường
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return new Intl.NumberFormat('vi-VN').format(value) + ' VND'; }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VND';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Biểu đồ Tỷ lệ Trạng thái Đơn hàng
        if (document.getElementById('orderStatusChart') && orderStatusLabels.length > 0) {
            const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');

            const cancelledStatusLabel = 'Đã hủy'; // Nhãn của trạng thái "Đã hủy"
            const cancelledColor = '#dc3545';       // Màu đỏ (Bootstrap danger) cho trạng thái "Đã hủy"
            const cancelledHoverColor = '#c82333';  // Màu đỏ đậm hơn khi hover

            // Bảng màu mặc định cho các trạng thái khác
            const defaultBackgroundColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#858796', '#5a5c69'];
            const defaultHoverBackgroundColors = ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#60626f', '#37383e'];

            let chartBackgroundColors = [];
            let chartHoverBackgroundColors = [];
            let defaultColorIndex = 0;

            orderStatusLabels.forEach(label => {
                if (label === cancelledStatusLabel) {
                    chartBackgroundColors.push(cancelledColor);
                    chartHoverBackgroundColors.push(cancelledHoverColor);
                } else {
                    chartBackgroundColors.push(defaultBackgroundColors[defaultColorIndex % defaultBackgroundColors.length]);
                    chartHoverBackgroundColors.push(defaultHoverBackgroundColors[defaultColorIndex % defaultHoverBackgroundColors.length]);
                    defaultColorIndex++;
                }
            });

            new Chart(orderStatusCtx, {
                type: 'doughnut', // Có thể đổi thành 'pie'
                data: {
                    labels: orderStatusLabels,
                    datasets: [{
                        data: orderStatusData,
                        backgroundColor: chartBackgroundColors, // Sử dụng mảng màu đã tạo động
                        hoverBackgroundColor: chartHoverBackgroundColors, // Sử dụng mảng màu hover đã tạo động
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%', // Cho doughnut, bỏ đi nếu là 'pie'
                    plugins: {
                        legend: {
                            position: 'bottom', // Hiển thị chú giải ở dưới
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' đơn';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            }); 
        }
    });
</script>
@endsection