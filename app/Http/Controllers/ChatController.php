<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    public function openChat(){
        $messages = session('messages', []);
        $users = session('users', []);
        $from = session('from', null);
        $to = session('to', null);
        $chat_id = session('chat_id', null);

        return view('pages.global_chat.chat_body', compact('messages', 'users', 'from', 'to', 'chat_id'));
    }

    public function newChat(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'reciver_id' => "required|exists:users,id",
            'sender_id' => "required|exists:users,id",
        ], [
            'reciver_id.required' => "Receiver ID is required",
            'sender_id.required' => "Sender ID is required",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $senderId = $request->sender_id;
        $reciver_id = $request->reciver_id;

        // Check if the chat already exists
        $chat = Chat::where(function ($query) use ($senderId, $reciver_id) {
            $query->where('sender_id', $senderId)
                  ->where('reciver_id', $reciver_id);
        })->orWhere(function ($query) use ($senderId, $reciver_id) {
            $query->where('sender_id', $reciver_id)
                  ->where('reciver_id', $senderId);
        })->first();

        // If chat doesn't exist, create a new one
        if (!$chat) {
            $chat = new Chat();
            $chat->sender_id = $senderId;
            $chat->reciver_id = $reciver_id;
            $chat->save();
        }

        // Fetch related messages and users for the view
        $messages = Message::where('chat_id', $chat->id)->get();
        $users = User::where('id', '!=', $senderId)->get();
        $from = User::find($senderId);
        $to = User::find($reciver_id);
        $chat_id = $chat->id;

        // Redirect to openChat with session data
        return redirect()->route('openChat')
            ->with('messages', $messages)
            ->with('users', $users)
            ->with('from', $from)
            ->with('to', $to)
            ->with('chat_id', $chat_id);
    }
}
