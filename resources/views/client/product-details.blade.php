@extends('layouts.user')

@section('title', $product->name)

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <!-- Product Image -->
                <div class="col-12 col-md-6 mb-5">
                    <div class="product-image">
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
                    </div>
                    <!-- Thumbnail Gallery -->
                    @if ($product->variants->count() > 0)
                        <div class="row mt-4">
                            @foreach ($product->variants->take(3) as $variant)
                                <div class="col-3">
                                    <img src="{{ asset('storage/' . $variant->image ?? $product->image) }}"
                                        class="img-fluid thumbnail" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="col-12 col-md-6 mb-5">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <div class="product-price mb-4">
                        <strong>{{ number_format($product->price, 0, ',', '.') }}VNĐ</strong>
                        @if ($product->original_price)
                            <span
                                class="text-muted ms-2">{{ number_format($product->original_price, 0, ',', '.') }}VNĐ</span>
                        @endif
                    </div>

                    <div class="product-description mb-4">
                        <p>{{ $product->description }}</p>
                    </div>

                    <div class="product-meta mb-4">
                        <p><strong>SKU:</strong> <span>{{ $product->sku }}</span></p>
                        <p><strong>Danh mục:</strong> <span>{{ $product->category->name }}</span></p>
                        <p><strong>Thương hiệu:</strong> <span>{{ $product->brand->name }}</span></p>
                        <p><strong>Số lượng hàng:</strong>
                            <span>{{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}</span>
                        </p>
                    </div>

                    <div class="product-actions">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="quantity-selector mb-4">
                                <label for="quantity" class="me-2">Số lượng:</label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                    style="width: 100px;" value="{{ $product->stock > 0 ? 1 : 0 }}" min="1"
                                    max="{{ $product->stock }}" {{ $product->stock > 0 ? '' : 'disabled' }}>
                            </div>
                            @if ($product->stock > 0)
                                <button class="btn btn-primary" type="submit">Thêm vào giỏ hàng</button>
                            @else
                                <button class="btn btn-danger" disabled>Hết hàng</button>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                            data-bs-target="#description" type="button" role="tab">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab"
                            data-bs-target="#specifications" type="button" role="tab">Specifications</button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="tab-pane fade" id="specifications" role="tabpanel">
                        <table class="table">
                            <tbody>
                                @if ($product->variants->count() > 0)
                                    @foreach ($product->variants->first()->getAttributes() as $key => $value)
                                        @if (!in_array($key, ['id', 'product_id', 'created_at', 'updated_at']))
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
                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
                button.disabled = true;

                // Send request to add to cart
                fetch('{{ route('cart.add') }}', {
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
