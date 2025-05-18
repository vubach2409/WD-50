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
                    <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products') }}">Cửa hàng</a></li>
                <li><a class="nav-link" href="{{ route('about') }}">Về chúng tôi</a></li>
                <li><a class="nav-link" href="{{ route('services') }}">Dịch vụ</a></li>
                <li><a class="nav-link" href="{{ route('blog') }}">Blog</a></li>
                <li><a class="nav-link" href="{{ route('contact') }}">Liên hệ với chúng tôi</a></li>
            </ul>

            {{-- <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li><a class="nav-link" href="{{ route('userclient') }}"><img
                                src="{{ asset('clients/images/user.svg') }}"></a></li>
                    <li><a class="nav-link" href="{{ route('cart.show') }}"><img
                                src="{{ asset('clients/images/cart.svg') }}"></a></li>
                </ul> --}}
                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5 d-flex align-items-center">
                    <li class="nav-item me-3">
                        @auth
                            <a class="nav-link p-0" href="{{ route('account') }}" title="Tài khoản">
                                <img src="{{ asset('clients/images/user.svg') }}" alt="User Icon"
                                    style="width:24px; height:24px;">
                            </a>
                        @else
                            <a class="nav-link p-0" href="{{ route('login') }}" title="Đăng nhập">
                                <img src="{{ asset('clients/images/user.svg') }}" alt="User Icon"
                                    style="width:24px; height:24px;">
                            </a>
                        @endauth
                    </li>

                    <li class="nav-item dropdown position-relative me-3" id="mini-cart-container">
                        <!-- Mini cart sẽ được load bằng JavaScript -->
                    </li>

                    <!-- Icon thông báo -->
                    <li class="nav-item dropdown position-relative">
                        <a class="nav-link position-relative p-0" href="#" id="notificationDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo">
                            <i class="fas fa-bell fa-lg"></i>

                            @if (!empty($unreadNotifications) && $unreadNotifications->count() > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 0.65rem; min-width: 18px; height: 18px; line-height: 18px;">
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
                                                <small
                                                    class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>

                                                {{ $notification->data['message'] ?? 'Bạn có thông báo mới' }}
                                                <br>
                                                <small
                                                    class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
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

                <style>
                    .btn-xs {
                        font-size: 0.7rem;
                        padding: 2px 6px;
                        line-height: 1;
                    }
                </style>



</nav>

<script>
    function loadMiniCart() {
        fetch('/mini-cart')
            .then(response => response.json())
            .then(data => {
                const container = document.querySelector('#mini-cart-container');
                container.innerHTML = '';

                if (data.empty) {
                    container.innerHTML = `
                        <a class="nav-link" href="javascript:void(0)" id="mini-cart-toggle">
            <img src="{{ asset('clients/images/cart.svg') }}">
            <span id="mini-cart-count"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                0
            </span>
        </a>
        <div id="mini-cart-dropdown" class="dropdown-menu p-2 shadow"
            style="min-width: 240px; max-width: 300px; display: none; left: 50%; transform: translateX(-50%);">
            <p class="text-center m-0">Giỏ hàng đang trống.</p>
        </div>
    `;

                    const toggle = document.getElementById('mini-cart-toggle');
                    const dropdown = document.getElementById('mini-cart-dropdown');
                    toggle.addEventListener('click', () => {
                        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                    });

                    return;
                }

                let itemsHTML = '';
                data.items.forEach(item => {
                    const image = item.variant?.image || item.product.image;
                    itemsHTML += `
                        <div class="d-flex align-items-center mb-2">
                            <img src="/storage/${image}" alt="${item.name}" style="width: 40px; height: 40px; object-fit: cover;" class="me-2 rounded">
                            <div>
                                <div class="fw-bold">${item.name}</div>
                                <div>Số lượng: ${item.quantity}</div>
                                <div>Giá: ${item.price.toLocaleString()} đ</div>
                            </div>
                        </div>
                    `;
                });

                container.innerHTML = `
                    <a class="nav-link" href="javascript:void(0)" id="mini-cart-toggle">
                        <img src="{{ asset('clients/images/cart.svg') }}">
                        <span id="mini-cart-count"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            ${data.total_quantity}
                        </span>
                    </a>
                    <div id="mini-cart-dropdown" class="dropdown-menu p-2 shadow"
                        style="min-width: 240px; max-width: 300px; display: none; left: 50%; transform: translateX(-50%);">
                        <div id="mini-cart-items" class="overflow-auto" style="max-height: 200px;">
                            ${itemsHTML}
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="fw-semibold">Tổng:</small>
                            <small id="mini-cart-total" class="text-danger fw-bold">
                                ${data.total_price.toLocaleString()}₫
                            </small>
                        </div>
                        <div class="mt-2 d-flex justify-content-between">
                            <a href="{{ route('cart.show') }}" class="btn btn-outline-primary btn-xs w-50 me-1">Xem</a>
                            <a href="{{ route('checkout') }}" class="btn btn-primary btn-xs w-50 ms-1">Thanh toán</a>
                        </div>
                    </div>
                `;

                // Toggle dropdown
                const toggle = document.getElementById('mini-cart-toggle');
                const dropdown = document.getElementById('mini-cart-dropdown');
                toggle.addEventListener('click', () => {
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                });
            });
    }

    // Load khi trang ready
    document.addEventListener('DOMContentLoaded', loadMiniCart);
</script>
