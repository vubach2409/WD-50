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
                        <h2 class="card-title mb-4">Order History</h2>

                        @if ($orders->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                <h5>No orders found</h5>
                                <p class="text-muted">You haven't placed any orders yet.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Start Shopping</a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Payment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td>{{ number_format($order->total, 0, ',', '.') }} VNĐ</td>
                                                <td>{{ $order->status }}</td>
                                                <td>{{ $order->payment_method }}</td>
                                                <td>
                                                    <a href="{{ route('account.orders.show', $order) }}"
                                                        class="badge bg-danger text-decoration-none">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
