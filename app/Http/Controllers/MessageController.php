<?php

namespace App\Http\Controllers;

use App\Events\Chat as EventsChat;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnCallback;

class MessageController extends Controller
{

    public function sendMessage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'reciver_id' => 'required',
            'message' => 'required',
        ], [
            'reciver_id.required' => 'Select Users to send message',
            'message.required' => 'Enter Your message',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $chat = Chat::findOrFail($request->chat_id);

        // Create a new message
        $message = new Message();
        $message->message = $request->message;
        $message->chat_id = $request->chat_id;
        $message->reciver_id = $request->reciver_id;
        $message->sender_id = Auth::id();
        $message->save();

        // Attach the message to the chat
        $chat->messages()->save($message);

        $messages = $chat->messages()->with(['sender', 'receiver'])->orderBy('created_at', 'asc')->get();

        event(new EventsChat($message)) ;

        $users = User::where('id', '!=', Auth::id())->get();
        $from = User::find(Auth::id());
        $to = User::find($request->reciver_id);

        // Redirect to openChat with session data
        return redirect()->route('openChat')
            ->with('messages', $messages)
            ->with('chat_id', $request->chat_id)
            ->with('users', $users)
            ->with('from', $from)
            ->with('to', $to);
    }


    public function getMessages($chat_id)
    {
        $messages = Message::with('sender', 'receiver')
            ->where('chat_id', $chat_id)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($messages);
    }
}
