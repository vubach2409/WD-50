@extends('layouts.user')

@section('title', 'Order History')

@section('content')
    <div class="container py-5 mt-5 mb-5">
        <div class="row">
            <div class="col-lg-3">
                @include('client.account.partials.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2>Danh sách đơn hàng</h2>

                        @php
                            $statusLabels = [
                                'pending' => [
                                    'label' => 'Chờ xác nhận',
                                    'class' => 'warning',
                                    'icon' => 'bi-clock-history',
                                ],
                                'shipping' => ['label' => 'Đang giao', 'class' => 'info', 'icon' => 'bi-truck'],
                                'completed' => [
                                    'label' => 'Đã giao',
                                    'class' => 'success',
                                    'icon' => 'bi-check-circle',
                                ],
                                'cancelled' => ['label' => 'Đã huỷ', 'class' => 'danger', 'icon' => 'bi-x-circle'],
                            ];
                        @endphp

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="orderTab" role="tablist">
                            @foreach ($statusLabels as $key => $info)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if ($loop->first) active @endif"
                                        id="tab-{{ $key }}" data-bs-toggle="tab"
                                        data-bs-target="#pane-{{ $key }}" type="button" role="tab"
                                        aria-controls="pane-{{ $key }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        <i class="bi {{ $info['icon'] }}"></i> {{ $info['label'] }}
                                        ({{ count($ordersByStatus[$key]) }})
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content mt-3">
                            @foreach ($ordersByStatus as $key => $orders)
                                <div class="tab-pane fade @if ($loop->first) show active @endif"
                                    id="pane-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}">
                                    @if (count($orders))
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Mã đơn</th>
                                                        <th>Khách hàng</th>
                                                        <th>Trạng thái</th>
                                                        <th>Tổng tiền</th>
                                                        <th>Phương thức thanh toán</th>
                                                        <th>Ngày đặt</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($orders as $order)
                                                        <tr>
                                                            <td>{{ $order->id }}</td>
                                                            <td>{{ $order->consignee_name }}</td>
                                                            <td><span
                                                                    class="badge bg-{{ $statusLabels[$key]['class'] }}">{{ $statusLabels[$key]['label'] }}</span>
                                                            </td>
                                                            <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                                            <td>{{ $order->payment_method }}</td>
                                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                            <td><a href="{{ route('account.orders.show', $order) }}"
                                                                    class="badge bg-danger text-decoration-none">
                                                                    View Details
                                                                </a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p>Không có đơn hàng nào.</p>
                                    @endif
                                </div>
                            @endforeach


                            {{-- <div class="mt-4">
                                {{ $order->links() }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>






        @endsection
