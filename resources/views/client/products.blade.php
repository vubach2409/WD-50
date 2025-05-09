@extends('layouts.user')

@section('title', 'Cửa hàng')

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">

            {{-- THÔNG BÁO --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow"
                    role="alert" style="z-index: 9999;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow"
                    role="alert" style="z-index: 9999;">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                {{-- FORM LỌC BÊN TRÁI --}}
                <div class="col-md-3 mb-4">
                    <form method="GET" action="{{ route('products') }}" class="p-3 rounded shadow-sm bg-light">

                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">Danh mục</label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" id="category_all"
                                        value="" {{ request('category') == '' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category_all">Tất cả</label>
                                </div>
                                @foreach ($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category"
                                            id="category_{{ $category->id }}" value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="category_{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">Sắp xếp</label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sort" id="sort_default"
                                        value="" {{ request('sort') == '' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sort_default">Mặc định</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sort" id="sort_price_asc"
                                        value="price_asc" {{ request('sort') == 'price_asc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sort_price_asc">Giá tăng dần</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sort" id="sort_price_desc"
                                        value="price_desc" {{ request('sort') == 'price_desc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sort_price_desc">Giá giảm dần</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sort" id="sort_newest"
                                        value="newest" {{ request('sort') == 'newest' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sort_newest">Mới nhất</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn btn-primary w-40 mt-2 fs-12 px-3 py-2 rounded-pill shadow-sm border-0 transition-all hover-shadow">
                                <i class="bi bi-funnel-fill me-1"></i> Lọc
                            </button>
                        </div>


                    </form>
                </div>

                {{-- DANH SÁCH SẢN PHẨM BÊN PHẢI --}}
                <div class="col-md-9">
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-12 col-sm-6 col-md-4 mb-4">
                                <div class="product-item position-relative overflow-hidden">
                                    <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none">
                                        <div class="product-image position-relative">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                                                alt="{{ $product->name }}">

                                            @if ($product->is_new)
                                                <span
                                                    class="badge bg-success position-absolute top-0 start-0 m-2">Mới</span>
                                            @elseif($product->price_sale < $product->price)
                                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">Hot</span>
                                            @endif
                                        </div>

                                        <div class="product-info p-3">
                                            <h5 class="product-title text-dark mb-1" style="font-size: 1rem;">
                                                {{ $product->name }}</h5>
                                            <p class="product-price mb-2">
                                                <span
                                                    class="text-danger fw-bold">{{ number_format($product->price_sale, 0, ',', '.') }}đ</span>
                                                @if ($product->price_sale < $product->price)
                                                    <span
                                                        class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="product-action-btn position-absolute top-50 start-50 translate-middle">
                                            <a href="{{ route('product.details', $product->id) }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-2 py-2">
                                                <i class="bi bi-eye me-1"></i> Xem thêm
                                            </a>
                                        </div>
                                    </a>
                                </div>
                            </div>


                        @empty
                            <p>Không có sản phẩm nào phù hợp.</p>
                        @endforelse
                    </div>
                </div>




                {{-- PHÂN TRANG --}}
                <div class="mt-4">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>


        {{-- PHÂN TRANG --}}
        <div class="mt-4">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
    </div>

    <!-- Chat Toggle Icon -->
    <div id="chat-toggle" class="position-fixed bottom-0 end-0 m-4 rounded-circle bg-primary text-white p-3 shadow"
        style="z-index: 1000; cursor: pointer;">
        💬
    </div>

    <!-- Chat Box -->
    <div id="chat-box" class="position-fixed bottom-0 end-0 m-4 card shadow"
        style="width: 300px; display: none; z-index: 1000;">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <span>Chat hỗ trợ</span>
            <button id="chat-close" class="btn-close btn-close-white btn-sm"></button>
        </div>
        <div id="chat-messages" class="card-body" style="height: 200px; overflow-y: auto; font-size: 14px;">
            <p class="text-muted">Chào quý khách, tôi có thể giúp gì cho bạn?</p>
        </div>
        <div class="card-footer">
            <div id="chat-suggestions" class="mb-2 d-flex flex-wrap gap-1">
                <button type="button" class="bg bg-outline-secondary ">Ghế gaming dưới 2 triệu</button>
                <button type="button" class="bg bg-outline-secondary ">Bàn làm việc</button>
                <button type="button" class="bg bg-outline-secondary ">Phụ kiện</button>
            </div>
            <form id="chat-form">
                <div class="input-group">
                    <input type="text" id="chat-input" class="form-control" placeholder="Nhập tin nhắn..." />
                    <button class="btn btn-primary" type="submit">Gửi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mở và đóng chat box
        document.getElementById('chat-toggle').addEventListener('click', function() {
            var chatBox = document.getElementById('chat-box');
            var chatMessages = document.getElementById('chat-messages');

            if (chatBox.style.display === 'none' || chatBox.style.display === '') {
                chatBox.style.display = 'block';
                if (chatMessages.querySelector('.text-muted') === null) {
                    var greetingMessage = document.createElement('p');
                    greetingMessage.classList.add('text-muted');
                    greetingMessage.textContent = 'Chào bạn! Bạn đang quan tâm sản phẩm nào để mình hỗ trợ nhé!!';
                    chatMessages.appendChild(greetingMessage);
                }
            } else {
                chatBox.style.display = 'none';
            }
        });

        document.getElementById('chat-close').addEventListener('click', function() {
            document.getElementById('chat-box').style.display = 'none';
        });

        // Gửi tin nhắn
        document.getElementById('chat-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var messageInput = document.getElementById('chat-input');
            var message = messageInput.value.trim();

            if (message !== '') {
                var chatMessages = document.getElementById('chat-messages');
                var newMessage = document.createElement('p');
                newMessage.textContent = "Bạn: " + message;
                chatMessages.appendChild(newMessage);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Hiển thị "Admin đang nhập..."
                const typingIndicator = document.createElement('p');
                typingIndicator.id = 'typing-indicator';
                typingIndicator.classList.add('text-muted', 'fst-italic');
                typingIndicator.textContent = 'Admin đang nhập...';
                chatMessages.appendChild(typingIndicator);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        var responseMessage = document.createElement('div');
                        responseMessage.innerHTML = 'Admin: ' + data.reply;
                        chatMessages.appendChild(responseMessage);
                        chatMessages.scrollTop = chatMessages.scrollHeight;

                        // Ẩn typing indicator sau khi nhận phản hồi
                        typingIndicator.style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Có lỗi xảy ra:', error);
                        typingIndicator.style.display = 'none'; // Ẩn typing indicator nếu có lỗi
                    });

                messageInput.value = '';
            }
        });

        // Gợi ý nhanh
        document.querySelectorAll('#chat-suggestions button').forEach(button => {
            button.addEventListener('click', function() {
                const messageInput = document.getElementById('chat-input');
                messageInput.value = this.textContent;
                document.getElementById('chat-form').dispatchEvent(new Event('submit'));
            });
        });
    </script>







@endsection