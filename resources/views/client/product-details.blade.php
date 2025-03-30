@extends('layouts.user')

@section('title', $product->name)

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <div class="row">
                <!-- Product Image -->
                <div class="col-12 col-md-6 mb-5">
                    <div class="product-image">
                        <img src="{{ asset($product->image) }}" class="img-fluid" alt="{{ $product->name }}">
                    </div>
                    <!-- Thumbnail Gallery -->
                    @if($product->variants->count() > 0)
                    <div class="row mt-4">
                        @foreach($product->variants->take(3) as $variant)
                        <div class="col-3">
                            <img src="{{ asset($variant->image ?? $product->image) }}" class="img-fluid thumbnail" alt="{{ $product->name }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="col-12 col-md-6 mb-5">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <div class="product-price mb-4">
                        <strong>${{ number_format($product->price, 2) }}</strong>
                        @if($product->original_price)
                        <span class="text-muted ms-2">${{ number_format($product->original_price, 2) }}</span>
                        @endif
                    </div>
                    
                    <div class="product-description mb-4">
                        <p>{{ $product->description }}</p>
                    </div>

                    <div class="product-meta mb-4">
                        <p><strong>SKU:</strong> <span>{{ $product->sku }}</span></p>
                        <p><strong>Category:</strong> <span>{{ $product->category->name }}</span></p>
                        <p><strong>Brand:</strong> <span>{{ $product->brand->name }}</span></p>
                        <p><strong>Stock:</strong> <span>{{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</span></p>
                    </div>

                    <div class="product-actions">
                        <div class="quantity-selector mb-4">
                            <label for="quantity" class="me-2">Quantity:</label>
                            <input type="number" id="quantity" class="form-control" style="width: 100px;" value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        
                        <button class="btn btn-primary" onclick="addToCart({{ $product->id }})">Add to Cart</button>
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="row mt-5">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">Specifications</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews</button>
                        </li>
                    </ul>

                    <div class="tab-content mt-4" id="productTabsContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <p>{{ $product->long_description }}</p>
                        </div>
                        <div class="tab-pane fade" id="specifications" role="tabpanel">
                            <table class="table">
                                <tbody>
                                    @if($product->variants->count() > 0)
                                        @foreach($product->variants->first()->getAttributes() as $key => $value)
                                            @if(!in_array($key, ['id', 'product_id', 'created_at', 'updated_at']))
                                            <tr>
                                                <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                                                <td>{{ $value }}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            @if($product->reviews?->count() > 0)
                                @foreach($product->reviews as $review)
                                <div class="review-item mb-4">
                                    <div class="d-flex justify-content-between">
                                        <h5>{{ $review->user->name }}</h5>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-muted">{{ $review->comment }}</p>
                                </div>
                                @endforeach
                            @else
                                <p>No reviews yet. Be the first to review this product!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
            button.disabled = true;

            // Send request to add to cart
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
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
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show mt-3';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    button.parentNode.insertBefore(alert, button.nextSibling);
                    
                    // Update cart count with animation
                    window.dispatchEvent(new CustomEvent('cartUpdate', {
                        detail: { 
                            count: data.cart_count,
                            message: data.message,
                            cartData: data.cart_data
                        }
                    }));
                } else {
                    // Show error message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    button.parentNode.insertBefore(alert, button.nextSibling);
                }
            })
            .catch(error => {
                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
                alert.innerHTML = `
                    An error occurred. Please try again.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                button.parentNode.insertBefore(alert, button.nextSibling);
            })
            .finally(() => {
                // Reset button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    </script>
    @endpush
@endsection 