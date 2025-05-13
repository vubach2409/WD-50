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
                        <!-- Mini cart sẽ được load bằng JavaScript -->
                    </li>





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