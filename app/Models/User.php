<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ChatRoom;
use App\Models\ChatMessage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * Chat rooms initiated by or belonging to this user.
     */
    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'user_id');
    }

    /**
     * Messages sent by this user.
     */
    public function messagesSent()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

}
