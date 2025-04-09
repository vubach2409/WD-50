@extends('layouts.user')

@section('title', 'Order History')

@section('content')
    <div class="container py-5 mt-5 mb-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow" role="alert"
                style="z-index: 9999;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow" role="alert"
                style="z-index: 9999;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-3">
                @include('client.account.partials.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
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
                                    id="pane-{{ $key }}" role="tabpanel"
                                    aria-labelledby="tab-{{ $key }}">
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
                                                                    class="badge bg-warning text-decoration-none">
                                                                    View Details
                                                                </a>
                                                                @if ($order->status == 'pending')
                                                                    <form
                                                                        action="{{ route('account.orders.cancel', $order->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button type="submit"
                                                                            class="badge bg-danger btn-sm">Hủy đơn</button>
                                                                    </form>
                                                                @endif

                                                                @php
                                                                    $hasRated = \App\Models\Feedbacks::where(
                                                                        'order_id',
                                                                        $order->id,
                                                                    )
                                                                        ->where('user_id', auth()->id())
                                                                        ->exists();
                                                                @endphp

                                                                @if ($order->status === 'completed' && !$hasRated)
                                                                    <a href="{{ route('orders.feedback', $order->id) }}"
                                                                        class="btn btn-sm btn-success">Đánh giá</a>
                                                                @elseif ($hasRated)
                                                                    <span class="badge bg-secondary">Đã đánh giá</span>
                                                                @endif


                                                            </td>
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
