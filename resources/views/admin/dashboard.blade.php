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
                                    Doanh Thu (Tháng {{ \Carbon\Carbon::now()->month }}/{{ \Carbon\Carbon::now()->year }})</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($totalRevenueThisMonth ?? 0, 0, ',', '.') }} VND</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Filter Form -->
        <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label for="start_date" class="form-label">Lọc từ ngày:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDateInput ?? '' }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label for="end_date" class="form-label">Đến ngày:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDateInput ?? '' }}">
                </div>
                <div class="col-md-3 align-self-md-end mb-2">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">Xóa lọc</a>
                </div>
            </div>            
        </form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Content Row - Charts -->
        <div class="row">
            <!-- Area Chart: Doanh thu 7 ngày gần nhất -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Doanh Thu
                            @if($startDateInput && $endDateInput)
                                từ {{ \Carbon\Carbon::parse($startDateInput)->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($endDateInput)->format('d/m/Y') }}
                            @else
                                7 Ngày Gần Nhất
                            @endif
                        </h6>
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
                        <h6 class="m-0 font-weight-bold text-primary">
                            Tỷ Lệ Trạng Thái Đơn Hàng
                            @if($startDateInput && $endDateInput)
                                ({{ \Carbon\Carbon::parse($startDateInput)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDateInput)->format('d/m/Y') }})
                            @else
                                (Tất cả)
                            @endif
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2" style="height: 320px;">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row - Best and Least Selling Products Charts -->
        <div class="row">
            <!-- Best Selling Products Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top 5 Sản Phẩm Bán Chạy Nhất</h6>
                    </div>
                    <div class="card-body">
                        @if (!empty($bestSellingProductLabels) && count($bestSellingProductLabels) > 0 && !empty($bestSellingProductData) && array_sum($bestSellingProductData) > 0)
                            <div class="chart-container" style="position: relative; height:320px; width:100%">
                                <canvas id="bestSellingProductsStoreChart"></canvas>
                            </div>
                        @else
                            <p class="text-center mt-3">
                                Không có dữ liệu sản phẩm bán chạy{{ $startDateInput || $endDateInput ? ' cho khoảng thời gian đã chọn' : '' }}.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Least Selling Products Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top 5 Sản Phẩm Bán Ế Nhất</h6>
                    </div>
                    <div class="card-body">
                        @if (!empty($leastSellingProductLabels) && count($leastSellingProductLabels) > 0)
                            <div class="chart-container" style="position: relative; height:320px; width:100%">
                                <canvas id="leastSellingProductsChart"></canvas>
                            </div>
                        @else
                             <p class="text-center mt-3">
                                Không có dữ liệu sản phẩm bán ế{{ $startDateInput || $endDateInput ? ' cho khoảng thời gian đã chọn' : '' }}.
                            </p>
                        @endif
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
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const today = new Date().toISOString().split('T')[0]; // Lấy ngày hiện tại dạng YYYY-MM-DD

            // Đặt giới hạn ngày kết thúc không được lớn hơn ngày hiện tại
            if (endDateInput) {
                endDateInput.setAttribute('max', today);
            }

            // Cập nhật giới hạn ngày kết thúc khi ngày bắt đầu thay đổi
            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    endDateInput.setAttribute('min', this.value);
                });
            }

            // Cập nhật giới hạn ngày bắt đầu khi ngày kết thúc thay đổi
            if (endDateInput && startDateInput) {
                endDateInput.addEventListener('change', function() {
                    startDateInput.setAttribute('max', this.value);
                });
            }
            // Đặt giới hạn ngày bắt đầu không được lớn hơn ngày hiện tại
            if (startDateInput) {
                startDateInput.setAttribute('max', today);
            }

            // Dữ liệu từ PHP (đảm bảo các biến này được truyền từ controller)
            const revenueLabels = @json($revenueChartLabels ?? []); // Cập nhật tên biến
            const revenueData = @json($revenueChartData ?? []);   // Cập nhật tên biến
            const orderStatusLabels = @json($orderStatusLabels ?? []);
            const orderStatusData = @json($orderStatusData ?? []);
            // Dữ liệu cho biểu đồ sản phẩm bán chạy
            const phpBestSellingProductLabels = @json($bestSellingProductLabels ?? []); // Renamed to avoid conflict
            const phpBestSellingProductData = @json($bestSellingProductData ?? []);   // Renamed to avoid conflict
            const leastSellingProductLabels = @json($leastSellingProductLabels ?? []);
            const leastSellingProductData = @json($leastSellingProductData ?? []);


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
                                    callback: function(value) {
                                        return new Intl.NumberFormat('vi-VN').format(value) + ' VND';
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('vi-VN').format(context
                                                .parsed.y) + ' VND';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            
            // Biểu đồ Top 5 Sản Phẩm Bán Chạy Nhất
if (document.getElementById('bestSellingProductsStoreChart') && phpBestSellingProductLabels.length > 0 && phpBestSellingProductData.reduce((a, b) => a + b, 0) > 0) {
    const bestSellingCtx = document.getElementById('bestSellingProductsStoreChart').getContext('2d');
    new Chart(bestSellingCtx, {
        type: 'bar', // Thay đổi từ 'pie' sang 'bar'
        data: {
            labels: phpBestSellingProductLabels,
            datasets: [{
                label: 'Số lượng bán',
                data: phpBestSellingProductData,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)', // Xanh dương
                    'rgba(75, 192, 192, 0.5)', // Xanh lá
                    'rgba(255, 206, 86, 0.5)', // Vàng
                    'rgba(153, 102, 255, 0.5)', // Tím
                    'rgba(255, 99, 132, 0.5)'  // Đỏ
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: Math.max(1, ...phpBestSellingProductData) > 5 ? undefined : 1,
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: false,
                }
            }
        }
    });
}

// Biểu đồ Top 5 Sản Phẩm Bán Ế Nhất
if (document.getElementById('leastSellingProductsChart') && leastSellingProductLabels.length > 0) {
    const leastSellingCtx = document.getElementById('leastSellingProductsChart').getContext('2d');
    new Chart(leastSellingCtx, {
        type: 'bar', // Giữ nguyên là 'bar'
        data: {
            labels: leastSellingProductLabels,
            datasets: [{
                label: 'Số lượng bán',
                data: leastSellingProductData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)', // Đỏ
                    'rgba(255, 159, 64, 0.5)', // Cam
                    'rgba(255, 205, 86, 0.5)', // Vàng
                    'rgba(75, 192, 192, 0.5)', // Xanh lá
                    'rgba(54, 162, 235, 0.5)'  // Xanh dương
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: Math.max(1, ...leastSellingProductData) > 5 ? undefined : 1,
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: false,
                }
            }
        }
    });
}
            if (document.getElementById('orderStatusChart') && orderStatusLabels.length > 0) {
                const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');

                const cancelledStatusLabel = 'Đã hủy'; // Nhãn của trạng thái "Đã hủy"
                const cancelledColor = '#dc3545'; // Màu đỏ (Bootstrap danger) cho trạng thái "Đã hủy"
                const cancelledHoverColor = '#c82333'; // Màu đỏ đậm hơn khi hover

                // Bảng màu mặc định cho các trạng thái khác
                const defaultBackgroundColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#858796', '#5a5c69'];
                const defaultHoverBackgroundColors = ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#60626f',
                    '#37383e'
                ];

                let chartBackgroundColors = [];
                let chartHoverBackgroundColors = [];
                let defaultColorIndex = 0;

                orderStatusLabels.forEach(label => {
                    if (label === cancelledStatusLabel) {
                        chartBackgroundColors.push(cancelledColor);
                        chartHoverBackgroundColors.push(cancelledHoverColor);
                    } else {
                        chartBackgroundColors.push(defaultBackgroundColors[defaultColorIndex %
                            defaultBackgroundColors.length]);
                        chartHoverBackgroundColors.push(defaultHoverBackgroundColors[defaultColorIndex %
                            defaultHoverBackgroundColors.length]);
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
                                        if (label) {
                                            label += ': ';
                                        }
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
