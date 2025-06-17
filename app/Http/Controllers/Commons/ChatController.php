<?php

namespace App\Http\Controllers\Commons;

use App\Events\UserTyping;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::where('user_id', user()->id)
            ->orWhere('admin_id', user()->id)
            ->with('messages')
            ->get();
        return ChatResource::collection($chats);
    }

    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);
        return view('chats.show', compact('chat'));
    }

    public function messages(Request $request, Chat $chat)
    {
        $perPage = 15;
        $query = $chat->messages()->latest();
        if ($request->has('before'))
            $query->where('id', '<', $request->before);
        $messages = $query->take($perPage)->get();
        return response()->json([
            'messages'        => $messages,
            'last_message_id' => $messages->isNotEmpty() ? $messages->first()->id : null
        ]);
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => ['required', 'string']
        ]);
        $this->authorize('view', $chat);
        $message = $chat->messages()->create([
            'user_id' => user()->id,
            'message' => $request->message
        ]);
        return response()->json($message);
    }

    public function markAsRead(Chat $chat)
    {
        $userId = user()->id;
        $chat->messages()
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->each(function ($message) {
                $message->read_at = now();
                $message->save();
            });
        return response()->json(['status' => 'success']);
    }
}
