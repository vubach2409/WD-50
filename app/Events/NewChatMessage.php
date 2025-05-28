<?php

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;
    public User $sender;

    /**
     * Create a new event instance.
     *
     * @param ChatMessage $message
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
        $this->sender = $message->sender; // Đảm bảo sender đã được load, ví dụ $message->load('sender') trước khi truyền vào event
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Kênh riêng tư cho mỗi phòng chat
        return new PrivateChannel('chat.' . $this->message->chat_room_id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new-message'; // Tên event mà client sẽ lắng nghe
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'chat_room_id' => $this->message->chat_room_id,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name, // Tên thật của người gửi
            'sender_role' => $this->sender->role, // Role của người gửi ('admin' hoặc 'user')
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }
}
