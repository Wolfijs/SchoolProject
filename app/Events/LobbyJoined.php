<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LobbyJoinController extends Controller
{
    public function join(Request $request, $id)
    {
        try {
            // Find the lobby
            $lobby = Lobby::find($id);
            
            if (!$lobby) {
                return response()->json(['message' => 'Lobby not found'], 404);
            }

            // Check if the user is already in the lobby (optional)
            if ($lobby->players()->where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'You are already in this lobby'], 400);
            }

            // Add the user to the lobby
            $lobby->players()->attach(Auth::id());

            // Increment the players count (assuming 'players_count' exists in Lobby model)
            $lobby->players_count += 1;
            $lobby->save();

            // Prepare data for response
            $data = [
                'lobby_id' => $lobby->id,
                'user_id' => Auth::id(),
                'nickname' => Auth::user()->name, // Assuming 'name' column holds the nickname
                'players_count' => $lobby->players_count,
            ];

            return response()->json(['message' => 'Successfully joined lobby', 'data' => $data], 200);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error joining lobby: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while joining the lobby.'], 500);
        }
    }
}
