<nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" aria-label="Furni navigation bar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">PoLy<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
            aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products') }}">Cửa hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Về chúng tôi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">Dịch vụ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('blog') }}">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
            </ul>

            <ul class="navbar-nav mb-2 mb-md-0 ms-5 d-flex align-items-center">
                <!-- Avatar user -->
                <li class="nav-item me-3">
                    @auth
                        <a class="nav-link p-0" href="{{ route('account') }}" title="Tài khoản">
                            <img src="{{ asset('clients/images/user.svg') }}" alt="User Icon" width="24" height="24">
                        </a>
                    @else
                        <a class="nav-link p-0" href="{{ route('login') }}" title="Đăng nhập">
                            <img src="{{ asset('clients/images/user.svg') }}" alt="User Icon" width="24"
                                height="24">
                        </a>
                    @endauth
                </li>

                <!-- Mini Cart -->
                <li class="nav-item dropdown position-relative me-3" id="mini-cart-container">
                    <!-- Mini cart will be loaded via JS -->
                </li>

                <!-- Notifications -->
                <li class="nav-item dropdown position-relative">
                    <a class="nav-link p-0 position-relative me-3" href="#" id="notificationDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo" with="24"
                        height="24">
                        <i class="fas fa-bell fa-lg"></i>
                        @if (!empty($unreadNotifications) && $unreadNotifications->count() > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>


                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-0"
                        aria-labelledby="notificationDropdown"
                        style="min-width: 320px; max-height: 350px; overflow-y: auto;">
                        <li class="p-3 border-bottom bg-light fw-semibold text-dark">
                            <i class="fas fa-bell me-2 text-warning"></i> Thông báo mới
                        </li>

                        @if (empty($unreadNotifications) || $unreadNotifications->isEmpty())
                            <li class="dropdown-item text-center text-muted py-4">
                                <i class="fas fa-check-circle me-1 text-success"></i> Không có thông báo mới
                            </li>
                        @else
                            @foreach ($unreadNotifications as $notification)
                                <li class="dropdown-item px-3 py-2">
                                    <a href="{{ route('account.orders.show', $notification->data['order_id'] ?? '#') }}"
                                        class="d-flex align-items-start text-decoration-none text-dark">
                                        <div class="me-3 mt-1">
                                            <i class="fas fa-receipt text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">
                                                {{ $notification->data['message'] ?? 'Bạn có thông báo mới' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach

                            <li>
                                <hr class="dropdown-divider m-0">
                            </li>

                            <li class="text-center py-2 bg-light">
                                <a href="{{ route('account.notifications.markAllRead') }}"
                                    class="btn btn-sm btn-outline-primary">Đánh dấu tất cả là đã đọc</a>
                            </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Custom Styles -->
<style>
    #mini-cart-dropdown {
        min-width: 360px !important;
        max-width: 400px !important;
        display: none;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 0.75rem;
    }

    #mini-cart-items {
        max-height: 220px;
        overflow-y: auto;
    }

    #mini-cart-items .cart-item {
        padding: 0.25rem 0;
        border-bottom: 1px solid #eee;
    }

    #mini-cart-items .cart-item:last-child {
        border-bottom: none;
    }

    #mini-cart-items img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .mini-cart-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #333;
    }

    .mini-cart-sub {
        font-size: 0.75rem;
        color: #777;
    }

    .mini-cart-empty {
        font-size: 0.875rem;
        color: #999;
        padding: 1rem 0;
    }

    .btn-xs {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        line-height: 1.2;
        border-radius: 0.375rem;
    }
</style>


<!-- JS for Mini Cart -->
<script>
    function loadMiniCart() {
        fetch('/mini-cart')
            .then(response => response.json())
            .then(data => {
                const container = document.querySelector('#mini-cart-container');
                container.innerHTML = '';

                const cartToggleHTML = `
                    <a class="nav-link" href="javascript:void(0)" id="mini-cart-toggle">
                        <img src="{{ asset('clients/images/cart.svg') }}">
                        <span id="mini-cart-count"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            ${data.total_quantity || 0}
                        </span>
                    </a>
                `;

                const itemsHTML = data.items?.map(item => {
                const image = item.variant?.image || item.product.image;
                const colorName = item.variant?.color_name || 'transparent';
                const sizeName = item.variant?.size_name || 'Không có kích thước';
                const variantName = item.variant?.variant_name || '';

                return `
                    <div class="d-flex align-items-center cart-item">
                        <img src="/storage/${image}" alt="${item.name}" class="me-2">
                        <div>
                            <div class="mini-cart-name">${item.name}</div>
                            <div class="mini-cart-sub">
                                ${variantName ? `Phân loại: ${variantName} | ` : ''}
                                <span style="display:inline-block; width:16px; height:16px; background-color:${colorName}; border:1px solid #ccc; border-radius:10px; vertical-align:middle;"></span>
                                - ${sizeName}
                            </div>
                            <div class="mini-cart-sub">Số lượng: ${item.quantity}</div>
                            <div class="mini-cart-sub">Giá: ${(item.price * item.quantity).toLocaleString()} ₫</div>
                        </div>
                    </div>
                `;
            }).join('') || `<div class="mini-cart-empty text-center"><i class="fas fa-shopping-cart me-1"></i> Giỏ hàng đang trống</div>`;

                const cartDropdownHTML = `
                    <div id="mini-cart-dropdown" class="dropdown-menu p-2 shadow"
                        style="min-width: 240px; max-width: 300px; display: none; left: 50%; transform: translateX(-50%);">
                        <div id="mini-cart-items" class="overflow-auto" style="max-height: 200px;">
                            ${itemsHTML}
                        </div>
                        ${!data.empty ? `
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="fw-semibold">Tổng:</small>
                            <small id="mini-cart-total" class="text-danger fw-bold">
                                ${data.total_price.toLocaleString()} ₫
                            </small>
                        </div>
                        <div class="mt-2 d-flex justify-content-between">
                            <a href="{{ route('cart.show') }}" class="btn btn-outline-primary btn-xs w-50 me-1">Xem</a>
                            <a href="{{ route('checkout') }}" class="btn btn-primary btn-xs w-50 ms-1">Thanh toán</a>
                        </div>` : ''}
                    </div>
                `;

                container.innerHTML = cartToggleHTML + cartDropdownHTML;

                const toggle = document.getElementById('mini-cart-toggle');
                const dropdown = document.getElementById('mini-cart-dropdown');
                toggle.addEventListener('click', () => {
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                });
            });
    }

    document.addEventListener('DOMContentLoaded', loadMiniCart);
</script>
