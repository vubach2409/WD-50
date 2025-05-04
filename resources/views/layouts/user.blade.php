<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('clients/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('clients/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('clients/css/style.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />



    <title>@yield('title', 'Web nội thất Poly')</title> 
</head>

<body>


    @include('client.blocks.header')

    @include('client.blocks.banner')

    <div class="container">
        <div class="content">
            @yield('content')
        </div>
    </div>

    @include('client.blocks.footer')


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
    <script src="{{ asset('clients/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('clients/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('clients/js/custom.js') }}"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

</body>

</html>
