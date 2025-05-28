<?php

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Kênh cho phòng chat cụ thể
Broadcast::channel('chat.{chatRoomId}', function ($user, $chatRoomId) {
    $chatRoom = ChatRoom::find($chatRoomId);

    if (!$chatRoom) {
        return false;
    }

    // Admin có thể truy cập bất kỳ phòng chat nào
    if ($user->role === User::ROLE_ADMIN) {
        return true;
    }

    // User chỉ có thể truy cập phòng chat của chính họ
    return (int) $user->id === (int) $chatRoom->user_id;
});
