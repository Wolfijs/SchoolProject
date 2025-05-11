<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use App\Models\LobbyMessage;
use App\Events\LobbyMessageSent;
use Illuminate\Http\Request;

class LobbyChatController extends Controller
{
    public function send(Request $request, Lobby $lobby)
    {
        // Check if user is in the lobby
        if (!$lobby->players->contains(auth()->id())) {
            return response()->json(['error' => 'You must be in the lobby to send messages'], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = LobbyMessage::create([
            'lobby_id' => $lobby->id,
            'user_id' => auth()->id(),
            'message' => $validated['message']
        ]);

        // Load the user relationship for the message
        $message->load('user');

        // Broadcast the message
        broadcast(new LobbyMessageSent($message))->toOthers();

        // Return the message data for immediate frontend update
        return response()->json([
            'status' => 'sent',
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'created_at' => $message->created_at,
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'photo' => $message->user->photo ? asset('storage/' . $message->user->photo) : asset('img/default.jpg')
                ]
            ]
        ]);
    }    
}


