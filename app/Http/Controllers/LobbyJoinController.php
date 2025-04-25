<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use App\Events\LobbyUpdated; // Import the event
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LobbyJoinController extends Controller
{
    public function join(Request $request, $id)
    {
        try {
            $lobby = Lobby::find($id);
            
            if (!$lobby) {
                return response()->json(['message' => 'Lobby not found'], 404);
            }

            // Check if the lobby is full
            if ($lobby->players_count >= $lobby->max_players) {
                return response()->json(['message' => 'Lobby is full'], 400);
            }

            if ($lobby->players()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'You are already in this lobby'], 400);
            }

            // Attach the user to the lobby and increment players count
            $lobby->players()->attach(Auth::id());
            $lobby->players_count++;
            $lobby->save();

            // Prepare the data for the response
            $data = [
                'lobby_id' => $lobby->id,
                'user_id' => Auth::id(),
                'nickname' => Auth::user()->name,
                'players_count' => $lobby->players_count,
                'max_players' => $lobby->max_players,
            ];

            // Broadcast the updated lobby information
            event(new LobbyUpdated($lobby));

            return response()->json(['message' => 'Successfully joined lobby', 'data' => $data], 200);
            
        } catch (\Exception $e) {
            \Log::error('Error joining lobby: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while joining the lobby.'], 500);
        }
    }

    public function leave(Request $request, $id)
    {
        try {
            $lobby = Lobby::find($id);
            
            if (!$lobby) {
                return response()->json(['message' => 'Lobby not found'], 404);
            }

            if (!$lobby->players()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'You are not in this lobby'], 400);
            }

            // Detach the user from the lobby and decrement players count
            $lobby->players()->detach(Auth::id());
            $lobby->players_count--;
            $lobby->save();

            // Broadcast the updated lobby information
            event(new LobbyUpdated($lobby));

            return response()->json(['message' => 'Successfully left lobby'], 200);
        } catch (\Exception $e) {
            \Log::error('Error leaving lobby: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while leaving the lobby.'], 500);
        }
    }
}
