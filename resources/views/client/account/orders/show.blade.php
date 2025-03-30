@extends('layouts.user')

@section('title', 'Order Details #' . $order->id)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            @include('client.account.partials.sidebar')
        </div>
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title mb-0">Order Details #{{ $order->id }}</h2>
                        <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Orders
                        </a>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Order Information</h5>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            @if($order->payment)
                                <p><strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $order->payment->status === 'completed' ? 'success' : ($order->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </p>
                                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment->payment_method) }}</p>
                                @if($order->payment->transaction_id)
                                    <p><strong>Transaction ID:</strong> {{ $order->payment->transaction_id }}</p>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Shipping Information</h5>
                            <p><strong>Name:</strong> {{ $order->name }}</p>
                            <p><strong>Email:</strong> {{ $order->email }}</p>
                            <p><strong>Phone:</strong> {{ $order->phone }}</p>
                            <p><strong>Address:</strong> {{ $order->address }}</p>
                            <p><strong>City:</strong> {{ $order->city }}</p>
                            <p><strong>Subdistrict:</strong> {{ $order->subdistrict }}</p>
                            <p><strong>ZIP Code:</strong> {{ $order->zip_code }}</p>
                            <p><strong>Country:</strong> {{ $order->country }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                    <td>Free</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                    <td>$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 