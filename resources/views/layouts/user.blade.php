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

    <title>Nội thất Poly</title>

    <style>
        .notification-container {
            position: fixed;
            top: 80px; /* Adjust this value based on your header height */
            right: 20px;
            z-index: 9999;
            max-width: 350px;
            pointer-events: none;
        }

        .notification {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 15px 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: auto;
        }

        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification.success {
            border-left: 4px solid #28a745;
        }

        .notification.error {
            border-left: 4px solid #dc3545;
        }

        .notification i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .notification.success i {
            color: #28a745;
        }

        .notification.error i {
            color: #dc3545;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .notification-message {
            color: #666;
            font-size: 0.9em;
        }

        .notification-close {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 0 5px;
            margin-left: 10px;
            font-size: 1.2em;
            transition: color 0.2s ease;
        }

        .notification-close:hover {
            color: #333;
        }

        .cart-item-remove {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mini-cart-item:hover .cart-item-remove {
            opacity: 1;
        }

        .quantity-control {
            transition: all 0.2s ease;
        }

        .quantity-control:hover {
            background-color: #f8f9fa;
        }

        .cart-item-image {
            transition: transform 0.2s ease;
        }

        .cart-item-image:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="notification-container" id="notificationContainer"></div>

    @include('client.blocks.header')

    @include('client.blocks.banner')

    <div class="container">
        <div class="content">
            @yield('content')
        </div>
    </div>

    @include('client.blocks.footer')

    <script src="{{ asset('clients/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('clients/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('clients/js/custom.js') }}"></script>

    <script>
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => {
                    container.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Listen for cart updates
        window.addEventListener('cartUpdate', function(e) {
            showNotification('Cart updated successfully');
        });
    </script>

    @stack('scripts')
</body>

</html>
