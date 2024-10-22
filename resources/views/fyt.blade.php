@extends('layouts.app')

@section('title', 'Find Your Teammates')

@section('content')
@include('partials.header')
@include('partials.sidebar')

<section id="find-teammates" class="find-teammates-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Find Your Perfect Teammates</h2>
            <p>Connect with players who match your style, skill level, and favorite games.</p>
        </div>
    </div>
</section>

<section id="game-lobbies" class="game-lobbies-section">
    <div class="container">
        @auth
            <button id="createLobbyBtn" class="btn-primarys">Create Lobby</button>
        @else
            <a href="{{ route('login') }}" class="btn-primarys">Login to Create Lobby</a>
        @endauth

        <h2 class="section-title">Available Game Lobbies</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="lobby-list" id="lobbyList">
            @foreach ($lobbies as $lobby)
                <div class="lobby-box" id="lobby-{{ $lobby->id }}">
                    <div class="lobby-image-container">
                        @php
                            // Determine the game image based on the lobby's game name
                            $gameImage = match($lobby->game_name) {
                                'Fortnite' => asset('img/fortnite.jpg'),
                                'CS 2' => asset('img/cs2.jpg'),
                                default => asset('img/games/default.jpg'),
                            };
                        @endphp

                        <img src="{{ $gameImage }}" alt="{{ $lobby->game_name }}" class="lobby-game-img">
                    </div>

                    <h3 class="game-name">{{ $lobby->game_name }}</h3>
                    <p><strong>Skill Level:</strong> {{ $lobby->skill_level }}</p>
                    <p><strong>Playstyle:</strong> {{ $lobby->playstyle }}</p>
                    <p><strong>Region:</strong> {{ $lobby->region }}</p>
                    <p><strong>Created By:</strong> {{ $lobby->creator_nickname }}</p>
                    <p><strong>Description:</strong> {{ $lobby->description }}</p>
                    <p><strong>Created At:</strong> {{ $lobby->created_at->format('F j, Y, g:i a') }}</p>
                    <p><strong>Players:</strong> <span class="lobby-players-count">{{ $lobby->players_count }}</span> / {{ $lobby->max_players }}</p>

                    @auth
                        @if ($lobby->players_count < $lobby->max_players)
                            @if (!$lobby->players->contains(auth()->id()))
                                <form action="{{ route('lobby.join', $lobby->id) }}" method="POST" class="join-lobby-form" data-lobby-id="{{ $lobby->id }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-primarys">Join Lobby</button>
                                </form>
                            @else
                                <p class="text-muted">You are already in this lobby.</p>
                                <form action="{{ route('chat.start', $lobby->id) }}" method="GET" style="display:inline;">
                                    <button type="submit" class="btn-primarys">Chat with Teammates</button>
                                </form>
                            @endif
                        @else
                            <p class="text-muted">Lobby is full.</p>
                            @if ($lobby->players->contains(auth()->id()))
                                <form action="{{ route('chat.start', $lobby->id) }}" method="GET" style="display:inline;">
                                    <button type="submit" class="btn-primarys">Chat with Teammates</button>
                                </form>
                            @endif
                        @endif

                        @if (auth()->id() === $lobby->user_id)
                        <form class="delete-lobby-form" action="/lobby/{{ $lobby->id }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Delete Lobby</button>
</form>

                        @endif
                    @else
                        <p class="text-muted">Login to join the lobby</p>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
</section>

@auth
<div id="createLobbyPopup" class="popup" style="display: none;">
    <div class="popup-content">
        <span class="close-popup">&times;</span>
        <h2>Create a New Lobby</h2>
        <form id="createLobbyForm" action="{{ route('lobby.store') }}" method="POST" class="create-lobby-form">
            @csrf
            <div class="form-group">
                <label for="game_name">Game Name</label>
                <input type="text" id="game_name" name="game_name" required class="form-control" placeholder="Enter Game Name">
            </div>
            <div class="form-group">
                <label for="skill_level">Skill Level</label>
                <select id="skill_level" name="skill_level" class="form-control" required>
                    <option value="" disabled selected>Select Skill Level</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>
            </div>
            <div class="form-group">
                <label for="playstyle">Playstyle</label>
                <input type="text" id="playstyle" name="playstyle" required class="form-control" placeholder="Enter Playstyle">
            </div>
            <div class="form-group">
                <label for="region">Region</label>
                <input type="text" id="region" name="region" required class="form-control" placeholder="Enter Region">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" placeholder="Enter a brief description" required></textarea>
            </div>
            <div class="form-group">
                <label for="max_players">Max Players</label>
                <input type="number" id="max_players" name="max_players" min="1" max="10" required class="form-control" placeholder="Enter Max Players">
            </div>
            <button type="submit" class="btn-primarys create-btn">Create Lobby</button>
        </form>
    </div>
</div>
@endauth

@include('partials.footer')

@endsection

@section('scripts')
<script src="{{ asset('js/script.js') }}" defer></script>
@endsection
