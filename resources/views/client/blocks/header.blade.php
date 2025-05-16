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

                <li class="nav-item dropdown position-relative" id="mini-cart-container">
                    <a class="nav-link" href="javascript:void(0)" id="mini-cart-toggle">
                        <img src="{{ asset('clients/images/cart.svg') }}">
                        <span id="mini-cart-count"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                    </a>

                    <!-- Mini Cart Dropdown -->
                    <div id="mini-cart-dropdown" class="dropdown-menu p-2 shadow"
                        style="min-width: 240px; max-width: 300px; display: none; left: 50%; transform: translateX(-50%); position: absolute;">
                        <div id="mini-cart-items" class="overflow-auto" style="max-height: 200px;"></div>

                        <!-- Tổng tiền & Nút Xem / Thanh toán -->
                        <div id="mini-cart-footer" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="fw-semibold">Tổng:</small>
                                <small id="mini-cart-total" class="text-danger fw-bold">0₫</small>
                            </div>
                            <div class="mt-2 d-flex justify-content-between">
                                <a href="{{ route('cart.show') }}"
                                    class="btn btn-outline-primary btn-xs w-50 me-1">Xem</a>
                                <a href="{{ route('checkout') }}" class="btn btn-primary btn-xs w-50 ms-1">Thanh
                                    toán</a>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- Icon thông báo -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fa-lg"></i>
                        @if ($unreadNotifications->count() > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notificationDropdown"
                        style="min-width: 300px; max-height: 300px; overflow-y: auto;">
                        @if ($unreadNotifications->isEmpty())
                            <li class="dropdown-item text-center text-muted py-3">
                                <i class="fas fa-check-circle me-1 text-success"></i> Không có thông báo mới
                            </li>
                        @else
                            @foreach ($unreadNotifications as $notification)
                                <li class="dropdown-item border-bottom small">
                                    <a href="{{ route('account.orders.show', $notification->data['order_id'] ?? '#') }}"
                                        class="text-dark text-decoration-none d-flex align-items-start">
                                        <i class="fas fa-receipt text-primary me-2 mt-1"></i>
                                        <div class="flex-grow-1">
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
                            <li class="dropdown-item text-center py-2">
                                <a href="{{ route('account.notifications.markAllRead') }}"
                                    class="btn btn-sm btn-outline-secondary">Đánh dấu tất cả là đã đọc</a>
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
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById("mini-cart-toggle");
        const dropdown = document.getElementById("mini-cart-dropdown");
        const footer = document.getElementById("mini-cart-footer");

        toggleBtn.addEventListener("click", function () {
            const isVisible = dropdown.style.display === "block";
            dropdown.style.display = isVisible ? "none" : "block";
            if (!isVisible) {
                loadMiniCart();
            }
        });

        document.addEventListener("click", function (event) {
            if (!dropdown.contains(event.target) && !toggleBtn.contains(event.target)) {
                dropdown.style.display = "none";
            }
        });

        window.loadMiniCart = function () {
            fetch('{{ route('mini.cart') }}')
                .then(res => res.json())
                .then(data => {
                    const itemsContainer = document.getElementById('mini-cart-items');
                    const countEl = document.getElementById('mini-cart-count');
                    const totalEl = document.getElementById('mini-cart-total');

                    if (!data.items || data.items.length === 0) {
                        itemsContainer.innerHTML = `
                            <div class="text-center text-muted py-3">Giỏ hàng của bạn đang trống.</div>
                        `;
                        countEl.innerText = 0;
                        totalEl.innerText = '0₫';
                        footer.style.display = 'none';
                        return;
                    }

                    let itemsHTML = '';
                    data.items.forEach(item => {
                        const imageUrl = item.variant?.image ?
                            '/storage/' + item.variant.image :
                            '/storage/' + item.product.image;

                        itemsHTML += `
                            <div class="d-flex mb-2">
                                <img src="${imageUrl}" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <div class="flex-grow-1">
                                    <div class="small fw-semibold">${item.name}</div>
                                    <div class="text-muted small">${item.quantity} × ${item.price.toLocaleString()}₫</div>
                                </div>
                            </div>
                        `;
                    });

                    itemsContainer.innerHTML = itemsHTML;
                    countEl.innerText = data.total_quantity;
                    totalEl.innerText = data.total_price.toLocaleString() + '₫';
                    footer.style.display = 'block';
                });
        };
    });
</script>
