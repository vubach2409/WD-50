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
                    <li class="nav-item dropdown position-relative" id="mini-cart-container">

                        <a class="nav-link" href="javascript:void(0)" id="mini-cart-toggle">
                            <img src="{{ asset('clients/images/cart.svg') }}">
                            <span id="mini-cart-count"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
                        </a>

                        <!-- Mini Cart Dropdown -->
                        <div id="mini-cart-dropdown" class="dropdown-menu dropdown-menu-end p-2 shadow"
                            style="min-width: 240px; max-width: 300px; display: none;">
                            <div id="mini-cart-items" class="overflow-auto" style="max-height: 200px;"></div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="fw-semibold">Tổng:</small>
                                <small id="mini-cart-total" class="text-danger fw-bold">0₫</small>
                            </div>
                            <div class="mt-2 d-flex justify-content-between">
                                <a href="{{ route('cart.show') }}"
                                    class="btn btn-outline-primary btn-sm w-50 me-1">Xem</a>
                                <a href="{{ route('checkout') }}" class="btn btn-primary btn-sm w-50 ms-1">Thanh
                                    toán</a>
                            </div>
                        </div>
                    </li>
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


                </ul>
            </div>
        </div>

    </nav>

    <script>
        function loadMiniCart() {
            fetch('{{ route('mini.cart') }}')
                .then(res => res.json())
                .then(data => {
                    let itemsHTML = '';
                    data.items.forEach(item => {
                        itemsHTML += `
    <div class="d-flex mb-2">
        <img src="/storage/${item.image}" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
        <div class="flex-grow-1">
            <div class="small fw-semibold">${item.name}</div>
            <div class="text-muted small">${item.quantity} × ${item.price.toLocaleString()}₫</div>
        </div>
    </div>
`;
                    });

                    document.getElementById('mini-cart-items').innerHTML = itemsHTML;
                    document.getElementById('mini-cart-count').innerText = data.total_quantity;
                    document.getElementById('mini-cart-total').innerText = data.total_price.toLocaleString() + '₫';
                });

        }

        document.addEventListener('DOMContentLoaded', function() {
            loadMiniCart();

            const toggle = document.getElementById('mini-cart-toggle');
            const dropdown = document.getElementById('mini-cart-dropdown');

            toggle.addEventListener('click', function() {
                dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display ===
                    '') ? 'block' : 'none';
            });

            document.addEventListener('click', function(e) {
                if (!document.getElementById('mini-cart-container').contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        });
    </script>
