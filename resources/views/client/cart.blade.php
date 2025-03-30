@extends('layouts.user')

@section('title', 'Shopping Cart')

@section('content')
    <div class="untree_co-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">Shopping Cart</h2>
                    
                    @if(count($items) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="img-thumbnail me-3" style="width: 80px;">
                                                <div>
                                                    <h5 class="mb-0">{{ $item['name'] }}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item['price'], 2) }}</td>
                                        <td>
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})">-</button>
                                                <input type="number" class="form-control text-center" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 99 }}" onchange="updateQuantity({{ $item['id'] }}, this.value)" data-product-id="{{ $item['id'] }}">
                                                <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})">+</button>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item['subtotal'], 2) }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="removeFromCart({{ $item['id'] }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>${{ number_format($total, 2) }}</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('products') }}" class="btn btn-outline-primary">Continue Shopping</a>
                            <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h3>Your cart is empty</h3>
                            <p class="text-muted">Add some products to your cart and they will appear here</p>
                            <a href="{{ route('products') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateQuantity(productId, quantity) {
            if (quantity < 1) return;
            
            const input = event.target;
            const originalValue = input.value;
            input.disabled = true;
            
            fetch('{{ route("cart.update") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count with animation
                    window.dispatchEvent(new CustomEvent('cartUpdate', {
                        detail: { 
                            count: data.cart_count,
                            message: 'Cart updated successfully'
                        }
                    }));
                    location.reload();
                } else {
                    input.value = originalValue;
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                input.value = originalValue;
                showNotification('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                input.disabled = false;
            });
        }

        function removeFromCart(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            button.disabled = true;

            fetch('{{ route("cart.remove") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count with animation
                    window.dispatchEvent(new CustomEvent('cartUpdate', {
                        detail: { 
                            count: data.cart_count,
                            message: 'Item removed from cart'
                        }
                    }));
                    location.reload();
                } else {
                    button.innerHTML = originalContent;
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                button.innerHTML = originalContent;
                showNotification('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                button.disabled = false;
            });
        }

        // Add smooth scrolling to quantity inputs
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('wheel', function(e) {
                e.preventDefault();
                const currentValue = parseInt(this.value);
                const newValue = e.deltaY < 0 ? currentValue + 1 : currentValue - 1;
                if (newValue >= parseInt(this.min) && newValue <= parseInt(this.max)) {
                    this.value = newValue;
                    updateQuantity(parseInt(this.dataset.productId), newValue);
                }
            });
        });
    </script>
    @endpush
@endsection
