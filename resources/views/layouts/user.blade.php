<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">


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
    @vite(['resources/css/app.css'])
    <style>
        /* Chat Widget Styles */
        #chat-widget-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #1a73e8; /* Changed to a slightly different blue */
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center; 
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #chat-widget-container {
            position: fixed;
            bottom: 90px;
            /* Above the icon */
            right: 20px;
            width: 360px; /* Slightly wider */
            /* Adjust as needed */
            max-height: 550px; /* Slightly taller */
            /* Adjust as needed */
            background-color: white;
            border: 1px solid #e0e0e0; /* Lighter border */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1001;
            display: none;
            /* Hidden by default */
            flex-direction: column;
        }

        #chat-widget-container .chat-header {
            background-color: #f5f5f5; /* Light gray header */
            padding: 12px 15px; /* More padding */
            border-bottom: 1px solid #e0e0e0; /* Lighter border */
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500; /* Medium font weight for header text */
        }

        #chat-widget-container .chat-header #close-chat-widget {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 0 10px;
            color: #5f6368; /* Darker gray for close icon */
        }
        #chat-widget-container .chat-header #close-chat-widget:hover {
            color: #202124; /* Darker on hover */
        }

        #chat-widget-container .chat-messages {
            flex-grow: 1; /* Allow messages to take available space */
            padding: 15px; /* More padding */
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px; /* Space between messages */
            background-color: #ffffff; /* Ensure white background */
            /* Adjust based on container height and header/input */
        }

        #chat-widget-container .message {
            padding: 10px 15px; /* More padding in messages */
            border-radius: 18px; /* More rounded corners */
            max-width: 75%; /* Slightly wider max-width */
            word-wrap: break-word;
            line-height: 1.4; /* Better readability */
        }

        #chat-widget-container .message.sent {
            background-color: #1a73e8; /* Consistent blue for sent messages */
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px; /* Flat corner for speech bubble effect */
        }

        #chat-widget-container .message.received {
            background-color: #e9e9eb; /* Lighter gray for received messages */
            color: #202124; /* Darker text for received */
            align-self: flex-start;
            border-bottom-left-radius: 4px; /* Flat corner */
        }
        #chat-widget-container .message strong {
            font-size: 0.9em;
            display: block; /* Make sender name take full width if needed */
            margin-bottom: 2px; /* Small space below sender name */
        }
        #chat-widget-container .message .time {
            font-size: 0.75em;
            color: #5f6368; /* Gray for time */
            text-align: right; /* Align time to the right within the message bubble */
            margin-top: 4px; /* Space above time */
        }
        #chat-widget-container .message.sent .time {
            color: rgba(255, 255, 255, 0.7); /* Lighter time for sent messages */
        }

        #chat-widget-container .chat-input {
            display: flex;
            padding: 10px 15px; /* Consistent padding */
            border-top: 1px solid #e0e0e0; /* Lighter border */
            background-color: #f5f5f5; /* Light gray background for input area */
            border-bottom-left-radius: 8px; /* Match container rounding */
            border-bottom-right-radius: 8px; /* Match container rounding */
        }

        #chat-widget-container .chat-input input[type="text"] {
            flex-grow: 1;
            border: 1px solid #d1d1d1; /* Slightly darker border for input */
            border-radius: 20px; /* Pill shape */
            padding: 10px 15px; /* Good padding */
            font-size: 0.95em;
            margin-right: 10px; /* Space before button */
            outline: none; /* Remove default outline */
            transition: border-color 0.2s ease-in-out;
        }
        #chat-widget-container .chat-input input[type="text"]:focus {
            border-color: #1a73e8; /* Blue border on focus */
        }

        #chat-widget-container .chat-input button[type="submit"] {
            background-color: #1a73e8; /* Blue button */
            color: white;
            border: none;
            border-radius: 20px; /* Pill shape */
            padding: 10px 15px; /* Good padding */
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s ease-in-out;
        }
        #chat-widget-container .chat-input button[type="submit"]:hover {
            background-color: #125abc; /* Darker blue on hover */
        }
    </style>



    <title>Nội Thất PoLy</title>
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
    @auth {{-- Chỉ hiển thị và chạy script chat box nếu user đã đăng nhập --}}
        <div id="chat-widget-icon">
            <i class="bi bi-chat-dots-fill"></i>
        </div>

        <div id="chat-widget-container">
            <div class="chat-header">
                <span>Hỗ trợ trực tuyến</span>
                <button id="close-chat-widget">&times;</button>
            </div>
            <div class="chat-messages" id="chat-widget-messages">
                <!-- Messages will be loaded here -->
            </div>
            <form class="chat-input" id="chat-widget-form">
                <input type="text" id="chat-widget-input" placeholder="Nhập tin nhắn..." autocomplete="off">
                <button type="submit">Gửi</button>
            </form>
        </div>

        <script type="module">
            document.addEventListener('DOMContentLoaded', () => {
                const chatWidgetIcon = document.getElementById('chat-widget-icon');
                const chatWidgetContainer = document.getElementById('chat-widget-container');
                const closeChatWidgetButton = document.getElementById('close-chat-widget');
                const messagesContainer = document.getElementById('chat-widget-messages');
                const messageForm = document.getElementById('chat-widget-form');
                const messageInput = document.getElementById('chat-widget-input');

                let chatRoomId = null;  
                let currentUserId = null;
                let currentUserName = '';
                let currentUserRole = '';
                let chatInitialized = false;

                const scrollToBottom = () => {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                };

                const formatTime = (isoString) => {
                    const date = new Date(isoString);
                    return date.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                };

                const appendMessage = (data, isOwnMessage = false) => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message');
                    let displayName = '';

                    if (isOwnMessage) {
                        messageDiv.classList.add('sent');
                        displayName = 'Bạn';
                    } else {
                        messageDiv.classList.add('received');
                        // For user view, all admin messages are just "Admin"
                        displayName = (data.sender_role === 'admin') ? 'Admin' : data.sender_name;
                    }

                    messageDiv.innerHTML = `
                    <div><strong>${displayName}</strong>: ${data.message}</div>
                    <div class="time">${formatTime(data.created_at)}</div>
                `;
                    messagesContainer.appendChild(messageDiv);
                    scrollToBottom();
                };

                async function initializeChat() {
                    if (chatInitialized) return;

                    try {
                        // Fetch chat room info (this endpoint needs to be created)
                        // It should return chat_room_id, current_user_id, current_user_name, current_user_role
                        const response = await fetch('{{ route('chat.widgetInfo') }}'); // Ensure this route exists
                        if (!response.ok) throw new Error('Failed to get chat info');
                        const data = await response.json();

                        chatRoomId = data.chat_room_id;
                        currentUserId = data.current_user_id;
                        currentUserName = data.current_user_name;
                        currentUserRole = data.current_user_role;

                        // Fetch initial messages
                        const messagesResponse = await fetch(`/chat/room/${chatRoomId}/messages`);
                        if (!messagesResponse.ok) throw new Error('Failed to fetch messages');
                        const messages = await messagesResponse.json();

                        messagesContainer.innerHTML = ''; // Clear loading/previous
                        messages.forEach(msg => appendMessage(msg, msg.sender_id === currentUserId));
                        scrollToBottom();

                        // Listen for new messages via Pusher
                        if (window.Echo) {
                            window.Echo.private(`chat.${chatRoomId}`)
                                .listen('.new-message', (eventData) => {
                                    if (eventData.sender_id !== currentUserId) {
                                        appendMessage(eventData, false);
                                    }
                                });
                        }
                        chatInitialized = true;
                    } catch (error) {
                        console.error('Error initializing chat:', error);
                        messagesContainer.innerHTML =
                            '<p style="text-align:center; color: red;">Không thể tải chat.</p>';
                    }
                }

                chatWidgetIcon.addEventListener('click', () => {
                    chatWidgetContainer.style.display = 'flex';
                    initializeChat(); // Initialize chat when box is opened
                });

                closeChatWidgetButton.addEventListener('click', () => {
                    chatWidgetContainer.style.display = 'none';
                });

                messageForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    if (!chatInitialized || !chatRoomId) return;

                    const messageContent = messageInput.value.trim();
                    if (messageContent === '') return;

                    const optimisticMessageData = {
                        message: messageContent,
                        sender_id: currentUserId,
                        sender_name: currentUserName,
                        sender_role: currentUserRole,
                        created_at: new Date().toISOString(),
                    };
                    appendMessage(optimisticMessageData, true);
                    messageInput.value = '';

                    try {
                        await fetch(`/chat/room/${chatRoomId}/messages`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                message: messageContent
                            })
                        });
                    } catch (error) {
                        console.error('Error sending message:', error);
                        alert('Không thể gửi tin nhắn.');
                        // Optionally, handle UI to show message failed to send
                    }
                });
            });
        </script>
    @endauth


    <script src="{{ asset('clients/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('clients/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('clients/js/custom.js') }}"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/js/app.js'])
    @stack('scripts')

</body>

</html>
