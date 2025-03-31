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
                    <li><a class="nav-link" href="{{ route('cart.show') }}"><img
                                src="{{ asset('clients/images/cart.svg') }}"></a></li>
                    <li>
                        <a class="nav-link" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </nav>
