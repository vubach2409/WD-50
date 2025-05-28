<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hiển thị danh sách chat (cho admin) hoặc chuyển hướng đến phòng chat của user
    public function index()
    {
        $user = Auth::user();

        if ($user->role === User::ROLE_ADMIN) {
            // Sắp xếp theo updated_at của chat_rooms để đưa các cuộc hội thoại mới nhất lên đầu
            // Bạn cần đảm bảo cột updated_at trên bảng chat_rooms được cập nhật khi có tin nhắn mới.
            // Nếu không, bạn có thể sắp xếp theo created_at hoặc một logic khác.
            $chatRooms = ChatRoom::with('user')->orderBy('updated_at', 'desc')->get();
            return view('admin.chat.index', compact('chatRooms', 'user'));
        } else {
            // User sẽ được tạo/lấy phòng chat của họ và chuyển hướng đến đó
            $chatRoom = ChatRoom::firstOrCreate(
                ['user_id' => $user->id],
                ['name' => 'Hỗ trợ cho ' . $user->name]
            );
            return redirect()->route('chat.show', $chatRoom->id);
        }
    }

    // Hiển thị một phòng chat cụ thể và tin nhắn của nó (Dành cho USER từ widget)
    public function show($chatRoomId)
    {
        $currentUser = Auth::user();
        $chatRoom = ChatRoom::with(['messages.sender', 'user'])->findOrFail($chatRoomId);

        // Nếu là admin cố gắng truy cập route này, chuyển hướng họ đến dashboard chat mới
        if ($currentUser->role === User::ROLE_ADMIN) {
            return redirect()->route('chat.index');
        }
        // User chỉ có thể truy cập phòng chat của chính họ
        if ($chatRoom->user_id !== $currentUser->id) {
            abort(403, 'Bạn không có quyền truy cập phòng chat này.');
        }

        // Lấy danh sách admin để hiển thị (nếu cần)
        $admins = User::where('role', User::ROLE_ADMIN)->get();

        return view('chat.show', compact('chatRoom', 'currentUser', 'admins'));
        // LƯU Ý: View 'chat.show' này sẽ cần được tạo lại hoặc điều chỉnh
        // nếu bạn muốn user có một trang chat riêng biệt thay vì chỉ widget.
        // Hiện tại, logic này giả định user vẫn dùng widget và route này cho widget.
        // Nếu user không có trang chat riêng, bạn có thể loại bỏ view 'chat.show'
        // và điều chỉnh logic chuyển hướng cho user ở phương thức index().
    }

    // API để lấy tin nhắn cho một phòng chat (dùng cho JS fetch)
    public function fetchMessages($chatRoomId)
    {
        $currentUser = Auth::user();
        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        // Ủy quyền
        if ($currentUser->role !== User::ROLE_ADMIN && $chatRoom->user_id !== $currentUser->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $chatRoom->messages()->with('sender')->get()->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'sender_role' => $message->sender->role,
                'created_at' => $message->created_at->toIso8601String(),
            ];
        });

        return response()->json($messages);
    }


    // Lưu tin nhắn mới
    public function storeMessage(Request $request, $chatRoomId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $user = Auth::user();
        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        // Ủy quyền: Đảm bảo user có thể gửi tin nhắn trong phòng này
        if ($user->role !== User::ROLE_ADMIN && $chatRoom->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized to send message to this room.'], 403);
        }

        $message = ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $user->id,
            'message' => $request->input('message'),
        ]);

        // Load sender relationship để sử dụng trong event
        $message->load('sender');

        // Broadcast event tới những người khác trong kênh
        try {
            broadcast(new NewChatMessage($message))->toOthers();
        } catch (\Exception $e) {
            Log::error("Pusher broadcast error: " . $e->getMessage());
            // Có thể trả về lỗi cho client nếu broadcast thất bại nghiêm trọng
        }


        // Trả về thông tin tin nhắn đã gửi (bao gồm thông tin người gửi)
        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'chat_room_id' => $message->chat_room_id,
            'sender_id' => $message->sender->id,
            'sender_name' => $message->sender->name,
            'sender_role' => $message->sender->role,
            'created_at' => $message->created_at->toIso8601String(),
        ]);
    }
    public function getWidgetInfo(Request $request)
{
    $user = Auth::user();
    // This will find an existing chat room for the user or create a new one
    $chatRoom = ChatRoom::firstOrCreate(
        ['user_id' => $user->id],
        ['name' => 'Hỗ trợ cho ' . $user->name] // Or any default name you prefer
    );

    return response()->json([
        'chat_room_id' => $chatRoom->id,
        'current_user_id' => $user->id,
        'current_user_name' => $user->name,
        'current_user_role' => $user->role,
    ]);
}

}
