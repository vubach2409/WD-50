<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['chat_room_id', 'sender_id', 'message'];

    /**
     * The chat room this message belongs to.
     */
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * The sender of this message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
