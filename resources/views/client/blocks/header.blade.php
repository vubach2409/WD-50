    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">PoLy<span>.</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
                aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsFurni">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item ">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products') }}">Shop</a></li>
                    <li><a class="nav-link" href="{{ route('about') }}">About us</a></li>
                    <li><a class="nav-link" href="{{ route('services') }}">Services</a></li>
                    <li><a class="nav-link" href="{{ route('blog') }}">Blog</a></li>
                    <li><a class="nav-link" href="{{ route('contact') }}">Contact us</a></li>
                </ul>

                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li>
                        @auth
                            <a class="nav-link" href="{{ route('account') }}">
                                <img src="{{ asset('clients/images/user.svg') }}">
                            </a>
                        @else
                            <a class="nav-link" href="{{ route('login') }}">
                                <img src="{{ asset('clients/images/user.svg') }}">
                            </a>
                        @endauth
                    </li>
                    <li class="position-relative">
                        <a class="nav-link" href="{{ route('cart') }}" id="cartIcon">
                            <img src="{{ asset('clients/images/cart.svg') }}">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count" style="display: {{ count(session('cart', [])) > 0 ? 'block' : 'none' }}">
                                {{ count(session('cart', [])) }}
                            </span>
                        </a>
                        <div class="mini-cart" id="miniCart">
                            <div class="mini-cart-header">
                                <h6 class="mb-0">Shopping Cart</h6>
                                <span class="cart-count-text">{{ count(session('cart', [])) }} item(s)</span>
                            </div>
                            <div class="mini-cart-items">
                                @php
                                    $cart = session('cart', []);
                                    $total = 0;
                                @endphp
                                @foreach($cart as $productId => $details)
                                    @php
                                        $product = \App\Models\Product::find($productId);
                                        if ($product) {
                                            $total += $details['quantity'] * $details['price'];
                                    @endphp
                                    <div class="mini-cart-item">
                                        <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="mini-cart-image cart-item-image">
                                        <div class="mini-cart-item-details">
                                            <h6 class="mb-0">{{ $details['name'] }}</h6>
                                            <small class="text-muted">{{ $details['quantity'] }} x ${{ number_format($details['price'], 2) }}</small>
                                        </div>
                                        <button class="btn btn-link btn-sm text-danger cart-item-remove" onclick="removeFromMiniCart({{ $productId }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @php
                                        }
                                    @endphp
                                @endforeach
                            </div>
                            <div class="mini-cart-footer">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total:</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('cart') }}" class="btn btn-primary btn-sm">View Cart</a>
                                    <a href="{{ route('checkout') }}" class="btn btn-outline-primary btn-sm">Checkout</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </nav>

    <style>
        .mini-cart {
            position: absolute;
            top: 100%;
            right: 0;
            width: 300px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .mini-cart.show {
            display: block;
        }

        .mini-cart-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mini-cart-items {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
        }

        .mini-cart-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .mini-cart-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 10px;
        }

        .mini-cart-footer {
            padding: 15px;
            border-top: 1px solid #eee;
        }

        .cart-count {
            transition: all 0.3s ease;
        }

        .cart-count.animate {
            animation: bounce 0.5s ease;
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartIcon = document.getElementById('cartIcon');
            const miniCart = document.getElementById('miniCart');
            let timeout;

            cartIcon.addEventListener('mouseenter', function() {
                clearTimeout(timeout);
                miniCart.classList.add('show');
            });

            cartIcon.addEventListener('mouseleave', function() {
                timeout = setTimeout(() => {
                    miniCart.classList.remove('show');
                }, 200);
            });

            miniCart.addEventListener('mouseenter', function() {
                clearTimeout(timeout);
            });

            miniCart.addEventListener('mouseleave', function() {
                miniCart.classList.remove('show');
            });

            // Update cart count with animation
            function updateCartCount(count) {
                const cartCount = document.querySelector('.cart-count');
                const cartCountText = document.querySelector('.cart-count-text');

                if (cartCount) {
                    if (count > 0) {
                        cartCount.style.display = 'block';
                        cartCount.textContent = count;
                        cartCount.classList.add('animate');
                        setTimeout(() => {
                            cartCount.classList.remove('animate');
                        }, 500);
                    } else {
                        cartCount.style.display = 'none';
                    }
                }

                if (cartCountText) {
                    cartCountText.textContent = `${count} item${count !== 1 ? 's' : ''}`;
                }
            }

            // Update mini cart content
            function updateMiniCart(cartData) {
                const miniCartItems = document.querySelector('.mini-cart-items');
                const totalElement = document.querySelector('.mini-cart-footer .d-flex span:last-child');

                // Update items
                if (miniCartItems) {
                    miniCartItems.innerHTML = cartData.items.map(item => `
                        <div class="mini-cart-item">
                            <img src="/storage/${item.image}" alt="${item.name}" class="mini-cart-image cart-item-image">
                            <div class="mini-cart-item-details">
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">${item.quantity} x $${parseFloat(item.price).toFixed(2)}</small>
                            </div>
                            <button class="btn btn-link btn-sm text-danger cart-item-remove" onclick="removeFromMiniCart(${item.id})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `).join('');
                }

                // Update total
                if (totalElement) {
                    totalElement.textContent = `$${parseFloat(cartData.total).toFixed(2)}`;
                }

                // Update cart count
                updateCartCount(cartData.count);
            }

            // Listen for cart updates
            window.addEventListener('cartUpdate', function(e) {
                if (e.detail.message) {
                    showNotification(e.detail.message);
                }
                if (e.detail.cartData) {
                    updateMiniCart(e.detail.cartData);
                }
            });
        });

        function removeFromMiniCart(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

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
                            message: 'Item removed from cart',
                            cartData: data.cart_data
                        }
                    }));
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('An error occurred. Please try again.', 'error');
            });
        }
    </script>
    @endpush
