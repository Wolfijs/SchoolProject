<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LobbyChatController extends Controller
{

    public function send(Request $request, Lobby $lobby)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
    
        $message = new Message();
        $message->user_id = auth()->id();
        $message->lobby_id = $lobby->id; // this is CRUCIAL
        $message->content = $request->message;
        $message->save();
    
        return redirect()->back();
    }    
}


