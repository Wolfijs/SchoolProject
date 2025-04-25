<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Lobby;
use App\Models\LobbyChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function startChat()
    {
        $messages = Message::with('user')->get();
        return view('chat', compact('messages'));
    }

    public function send(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        try {
            $message = Message::create([
                'user_id' => auth()->id(),
                'content' => $validatedData['message'],
            ]);

            broadcast(new MessageSent($message))->toOthers();

            return response()->json([
                'message' => [
                    'username' => auth()->user()->name ?? 'Guest',
                    'content' => $message->content,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to send message.'], 500);
        }
    }

    public function sendLobbyMessage(Request $request, Lobby $lobby)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        if (!$lobby->players->contains(auth()->id())) {
            return back()->with('error', 'Jums ir jāpievienojas vestibilam, lai varētu rakstīt.');
        }

        $message = new LobbyChatMessage();
        $message->content = $request->message;
        $message->user_id = auth()->id();
        $message->lobby_id = $lobby->id;
        $message->save();

        // Ja nepieciešams, vari arī izmantot notikumu
        // broadcast(new LobbyMessageSent($message))->toOthers();

        return back();
    }

    public function loadMessages()
    {
        $messages = Message::with('user')->paginate(10);
        return response()->json(['messages' => $messages->map([$this, 'messageResponse'])]);
    }

    private function messageResponse($message)
    {
        return [
            'message' => $message->content,
            'user' => [
                'id' => $message->user_id,
                'name' => $message->user->name,
            ],
        ];
    }
}
