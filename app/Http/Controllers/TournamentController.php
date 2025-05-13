<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::with(['creator', 'participants'])
            ->latest()
            ->paginate(10);

        return view('tournaments.index', compact('tournaments'));
    }

    public function show(Tournament $tournament)
    {
        $tournament->load(['creator', 'participants']);
        return view('tournaments.show', compact('tournament'));
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'game' => 'required|string|max:100',
            'platform' => 'required|string|max:50',
            'external_link' => 'nullable|url|max:255',
            'max_players' => 'required|integer|min:2',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $tournament = Tournament::create([
            'user_id' => Auth::id(),
            'current_players' => 1,
            ...$validated
        ]);

        $tournament->participants()->attach(Auth::id());

        return redirect()->route('tournaments.show', $tournament);
    }

    public function join(Tournament $tournament)
    {
        if ($tournament->isFull()) {
            return back()->with('error', 'Turnīrs ir pilns!');
        }

        if ($tournament->hasParticipant(Auth::user())) {
            return back()->with('error', 'Jūs jau esat šī turnīra dalībnieks!');
        }

        $tournament->participants()->attach(Auth::id());
        $tournament->increment('current_players');

        return back();
    }

    public function leave(Tournament $tournament)
    {
        if (!$tournament->hasParticipant(Auth::user())) {
            return back()->with('error', 'Jūs neesat šī turnīra dalībnieks!');
        }

        $tournament->participants()->detach(Auth::id());
        $tournament->decrement('current_players');

        return back();
    }

    public function destroy(Tournament $tournament)
    {
        if (Auth::id() !== $tournament->user_id) {
            abort(403);
        }
        $tournament->delete();
        return redirect()->route('tournaments.index');
    }
} 