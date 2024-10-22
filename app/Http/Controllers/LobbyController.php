<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use Illuminate\Http\Request;

class LobbyController extends Controller
{
    public function index()
    {
        $lobbies = Lobby::with('players')->get();
        return view('fyt', compact('lobbies'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'You must be logged in to create a lobby.'], 403);
        }
    
        $request->validate([
            'game_name' => 'required|string|max:255',
            'skill_level' => 'required|string|max:50',
            'playstyle' => 'required|string|max:50',
            'region' => 'required|string|max:100',
            'description' => 'nullable|string',
            'max_players' => 'required|integer|min:1|max:10',
        ]);
    
        $lobby = new Lobby();
        $lobby->game_name = $request->game_name;
        $lobby->skill_level = $request->skill_level;
        $lobby->playstyle = $request->playstyle;
        $lobby->region = $request->region;
        $lobby->description = $request->description ?? '';
        $lobby->max_players = $request->max_players;
        $lobby->creator_nickname = auth()->user()->name;
        $lobby->players_count = 1;
        $lobby->user_id = auth()->id();
        $lobby->save();
    
        $lobby->players()->attach(auth()->id());

        return response()->json(['message' => 'Lobby created successfully!', 'redirect_url' => route('fyt')]);
    }

    public function join(Request $request, $id)
    {
        $lobby = Lobby::findOrFail($id);
        
        if ($lobby->players_count >= $lobby->max_players) {
            return response()->json(['error' => 'Lobby is full.'], 403);
        }

        $lobby->players()->attach(auth()->id());
        $lobby->increment('players_count');

        return response()->json(['message' => 'You have joined the lobby.']);
    }

    public function startChat($id)
    {
        return redirect()->route('chat.start', ['lobby' => $id]);
    }

    public function destroy($id)
    {
        $lobby = Lobby::findOrFail($id);
    
        if (auth()->id() !== $lobby->user_id) {
            return response()->json(['error' => 'You are not authorized to delete this lobby.'], 403);
        }
    
        $lobby->players()->detach();
        $lobby->delete();
    
        return response()->json(['message' => 'Lobby deleted successfully.']);
    }
    
    
}
