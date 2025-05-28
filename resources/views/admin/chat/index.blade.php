{{-- File: resources/views/admin/chat/index.blade.php --}}
@extends('layouts.admin') {{-- Sử dụng layout admin của bạn --}}

@section('title', 'Quản lý Chat')

@section('content') {{-- Hoặc section content chính trong layout admin của bạn --}}
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Quản lý Chat</h1>

        <div class="row">
            <!-- Danh sách các cuộc hội thoại (Users) -->
            <div class="col-lg-4 col-md-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách Hội thoại</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" id="chat-room-list" style="max-height: 600px; overflow-y: auto;">
                            @if($chatRooms->isEmpty())
                                <a href="#" class="list-group-item list-group-item-action">Chưa có hội thoại nào.</a>
                            @else
                                @foreach($chatRooms as $room)
                                    <a href="#"
                                       class="list-group-item list-group-item-action chat-room-item"
                                       data-room-id="{{ $room->id }}"
                                       data-user-name="{{ $room->user->name }}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $room->user->name }}</h6>
                                            {{-- TODO: Thêm thông tin tin nhắn cuối hoặc thời gian ở đây --}}
                                            {{-- <small class="text-muted">{{ $room->updated_at->diffForHumans() }}</small> --}}
                                        </div>
                                        {{-- TODO: Thêm chỉ báo tin nhắn chưa đọc --}}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Khu vực hiển thị tin nhắn -->
            <div class="col-lg-8 col-md-7">
                <div class="card shadow mb-4" id="chat-area" style="display: none;"> {{-- Ẩn ban đầu --}}
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary" id="chat-with-user-name">Chọn một hội thoại</h6>
                        {{-- Nút đóng có thể không cần thiết nếu luôn hiển thị một chat nào đó --}}
                    </div>
                    <div class="card-body chat-messages-container" id="admin-chat-messages">
                        <p class="text-center text-muted h-100 d-flex align-items-center justify-content-center" id="select-chat-prompt">Vui lòng chọn một hội thoại từ danh sách bên trái.</p>
                    </div>
                    <div class="card-footer chat-input-container" style="display: none;"> {{-- Ẩn ban đầu --}}
                        <form id="admin-message-form" class="d-flex">
                            <div class="flex-grow-1 mr-2"> {{-- Wrapper div for the input --}}
                                <input type="text" id="admin-message-input" class="form-control" placeholder="Nhập tin nhắn..." autocomplete="off">
                            </div>
                            <div> {{-- Wrapper div for the button --}}
                                <button type="submit" class="btn btn-primary">Gửi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Biến ẩn để lưu thông tin admin hiện tại --}}
    <input type="hidden" id="current-admin-id" value="{{ Auth::id() }}">
    <input type="hidden" id="current-admin-name" value="{{ Auth::user()->name }}">
@endsection

@section('styles')
<style>
    .chat-messages-container {
        height: 400px; /* Điều chỉnh chiều cao theo ý muốn */
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        padding: 15px;
        background-color: #f8f9fa; /* Màu nền nhẹ cho vùng chat */
    }
    .message {
        margin-bottom: 10px;
        padding: 10px 15px;
        border-radius: 18px;
        max-width: 70%;
        word-wrap: break-word;
        line-height: 1.4;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .message.sent { /* Tin nhắn admin gửi */
        background-color: #007bff;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
    .message.received { /* Tin nhắn user gửi */
        background-color: #e9ecef; /* Màu xám nhạt hơn cho tin nhắn nhận */
        color: #212529;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
    }
    .message strong {
        font-size: 0.85em; /* Kích thước tên người gửi nhỏ hơn một chút */
        display: block;
        margin-bottom: 3px;
        color: #495057;
    }
    .message.sent strong {
        color: rgba(255,255,255,0.85);
    }
    .message .time {
        font-size: 0.7em; /* Thời gian nhỏ hơn */
        color: #6c757d;
        text-align: right;
        margin-top: 5px;
    }
    .message.sent .time {
        color: rgba(255,255,255,0.7);
    }
    .list-group-item {
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .list-group-item.active {
        background-color: #007bff;
        border-color: #007bff;
        color: white !important; /* Quan trọng để ghi đè màu chữ mặc định */
    }
    .list-group-item.active h6,
    .list-group-item.active small {
        color: white !important;
    }
    .chat-input-container { /* Ensure this container allows flex children to behave as expected */
        padding: 1rem 1.25rem; /* Standard card-footer padding */
    }
    .chat-input-container input[type="text"] {
        border-radius: 20px;
    }
    .chat-input-container button {
        border-radius: 20px;
    }
    #select-chat-prompt {
        font-size: 1.1rem;
    }
</style>
@endsection

@section('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const chatRoomList = document.getElementById('chat-room-list');
        const chatArea = document.getElementById('chat-area');
        const chatWithUserName = document.getElementById('chat-with-user-name');
        const messagesContainer = document.getElementById('admin-chat-messages');
        const messageForm = document.getElementById('admin-message-form');
        const messageInput = document.getElementById('admin-message-input');
        const selectChatPrompt = document.getElementById('select-chat-prompt');
        const chatInputContainer = document.querySelector('.chat-input-container');

        const currentAdminId = parseInt(document.getElementById('current-admin-id').value);
        const currentAdminName = document.getElementById('current-admin-name').value;

        let activeChatRoomId = null;
        let currentEchoChannelName = null; // Đổi tên biến để rõ ràng hơn

        const scrollToBottom = () => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        };

        const formatTime = (isoString) => {
            if (!isoString) return '';
            const date = new Date(isoString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        };

        const appendMessage = (data, isOwnMessage = false) => {
            if (selectChatPrompt && messagesContainer.contains(selectChatPrompt)) {
                 selectChatPrompt.style.display = 'none'; // Ẩn prompt nếu nó còn đó
            }
            // Nếu tin nhắn đầu tiên là "Chưa có tin nhắn nào", xóa nó đi
            const noMessagesP = messagesContainer.querySelector('p.text-center.text-muted');
            if (noMessagesP && noMessagesP.textContent.includes("Chưa có tin nhắn nào")) {
                messagesContainer.innerHTML = '';
            }


            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            let displayName = '';

            if (isOwnMessage) {
                messageDiv.classList.add('sent');
                displayName = 'Bạn'; // Admin hiện tại
            } else {
                messageDiv.classList.add('received');
                displayName = data.sender_name + (data.sender_role === 'admin' ? ' (Admin)' : '');
            }

            messageDiv.innerHTML = `
                <div><strong>${displayName}</strong>: ${data.message}</div>
                <div class="time">${formatTime(data.created_at)}</div>
            `;
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        };

        const loadMessages = async (roomId, userName) => {
            if (selectChatPrompt) selectChatPrompt.style.display = 'none';
            messagesContainer.innerHTML = '<p class="text-center text-muted p-5">Đang tải tin nhắn...</p>';
            chatArea.style.display = 'block';
            chatInputContainer.style.display = 'flex'; // Hiển thị input
            chatWithUserName.textContent = `Chat với: ${userName}`;
            activeChatRoomId = roomId; // Gán ID phòng chat đang active

            try {
                const response = await fetch(`/chat/room/${roomId}/messages`); // Route API để lấy tin nhắn
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
                }
                const messages = await response.json();

                messagesContainer.innerHTML = '';
                if (messages.length === 0) {
                    messagesContainer.innerHTML = '<p class="text-center text-muted p-5">Chưa có tin nhắn nào trong hội thoại này.</p>';
                } else {
                    messages.forEach(msg => {
                        appendMessage(msg, msg.sender_id === currentAdminId);
                    });
                }
                scrollToBottom();
                subscribeToChannel(roomId); // Đăng ký kênh Pusher cho phòng này
            } catch (error) {
                console.error('Lỗi tải tin nhắn:', error);
                messagesContainer.innerHTML = `<p class="text-center text-danger p-5">Lỗi khi tải tin nhắn: ${error.message}</p>`;
            }
        };
        const subscribeToChannel = (roomId) => {
            if (currentEchoChannelName && window.Echo) {
                window.Echo.leave(currentEchoChannelName); // Rời kênh cũ
            }
            
            currentEchoChannelName = `chat.${roomId}`; // Kênh private cho phòng chat

            if (window.Echo) {
                window.Echo.private(currentEchoChannelName)
                    .listen('.new-message', (eventData) => {
                        // console.log('Admin received Pusher event:', eventData);
                        // Chỉ thêm nếu tin nhắn thuộc phòng chat đang active VÀ không phải của admin hiện tại
                        if (eventData.chat_room_id == activeChatRoomId && eventData.sender_id !== currentAdminId) {
                            appendMessage(eventData, false);
                        }
                    })
                    .error((error) => {
                        console.error(`[subscribeToChannel] Pusher subscription error for ${currentEchoChannelName}:`, error);
                        // Có thể hiển thị thông báo lỗi cho admin tại đây
                    });
            } else {
                console.error('Laravel Echo is not available.');
            }
        };

        if (chatRoomList) {
            chatRoomList.addEventListener('click', function(e) {
                // Ngăn chặn hành vi mặc định của thẻ <a> ngay từ đầu
                e.preventDefault();

                const targetItem = e.target.closest('.chat-room-item');

                if (targetItem) {
                    const currentActive = chatRoomList.querySelector('.list-group-item.active');
                    if (currentActive) {
                        currentActive.classList.remove('active');
                    }
                    targetItem.classList.add('active');

                    const roomId = targetItem.dataset.roomId;
                    const userName = targetItem.dataset.userName;

                    if (roomId) {
                        loadMessages(roomId, userName);
                    }
                }
            });
        }


        if (messageForm) {
            messageForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (!activeChatRoomId) {
                    alert('Vui lòng chọn một hội thoại để nhắn tin.');
                    return;
                }

                const messageContent = messageInput.value.trim();
                if (messageContent === '') return;

                const optimisticMessageData = {
                    message: messageContent,
                    sender_id: currentAdminId,
                    sender_name: currentAdminName,
                    sender_role: 'admin',
                    created_at: new Date().toISOString(),
                    chat_room_id: parseInt(activeChatRoomId) // Đảm bảo là số
                };
                appendMessage(optimisticMessageData, true);
                messageInput.value = '';

                try {
                    const response = await fetch(`/chat/room/${activeChatRoomId}/messages`, { // Route API để gửi tin nhắn
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ message: messageContent })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
                    }
                } catch (error) {
                    console.error('Lỗi gửi tin nhắn:', error);
                    alert('Lỗi gửi tin nhắn: ' + error.message);
                    // Xử lý rollback tin nhắn optimistic nếu cần
                }
            });
        }

    });
</script>
@endsection
