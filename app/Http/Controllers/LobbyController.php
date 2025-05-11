<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use App\Models\LobbyMessage;
use App\Events\LobbyMessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LobbyController extends Controller
{
    public function index()
    {
        $lobbies = Lobby::with('user')->latest()->get();
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
            'photo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
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

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('lobbies', 'public');
            $lobby->photo = $photoPath;
        }

        $lobby->save();
        $lobby->players()->attach(auth()->id());

        return response()->json([
            'message' => 'Lobby created successfully!',
            'redirect_url' => route('lobby.show', $lobby->id)
        ]);
    }

    public function join(Request $request, $id)
    {
        $lobby = Lobby::findOrFail($id);

        if ($lobby->players_count >= $lobby->max_players) {
            return redirect()->route('fyt')->with('error', 'Vestibils ir pilns.');
        }

        if ($lobby->players->contains(auth()->id())) {
            return redirect()->route('fyt')->with('error', 'Jūs jau esat pievienojies šim vestibils.');
        }

        $lobby->players()->attach(auth()->id());
        $lobby->increment('players_count');

        return redirect()->route('lobby.show', $lobby->id);
    }

    public function show(Lobby $lobby)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user is a member of the lobby
        if (!$lobby->players->contains(Auth::id())) {
            return redirect()->route('fyt')->with('error', 'Jums nav tiesības pievienoties šim vestibilam.');
        }

        $messages = LobbyMessage::with('user')
            ->where('lobby_id', $lobby->id)
            ->latest()
            ->get();

        return view('lobby.show', compact('lobby', 'messages'));
    }

    public function leave(Lobby $lobby)
    {
        if ($lobby->players->contains(auth()->id())) {
            $lobby->players()->detach(auth()->id());
            $lobby->decrement('players_count');
        }

        return redirect()->route('fyt')->with('success', 'Jūs izgājat no vestibila.');
    }

    public function destroy($id)
    {
        $lobby = Lobby::findOrFail($id);

        if (auth()->id() !== $lobby->user_id) {
            return response()->json(['error' => 'Jums nav tiesību dzēst šo vestibilu.'], 403);
        }

        $lobby->players()->detach();

        if ($lobby->photo) {
            Storage::disk('public')->delete($lobby->photo);
        }

        $lobby->delete();

        return response()->json([
            'message' => 'Vestibils veiksmīgi izdzēsts.',
            'redirect_url' => route('fyt')
        ]);
    }


    public function sendMessage(Request $request, Lobby $lobby)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
    
        $message = LobbyMessage::create([
            'lobby_id' => $lobby->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
    
        $message->load('user');
    
        broadcast(new LobbyMessageSent($message))->toOthers();
    
        return response()->json(['status' => 'sent']);
    }
}
