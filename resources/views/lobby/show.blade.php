@extends('layouts.app')

@section('title', 'Game Lobby - ' . $lobby->game_name)

@section('head')
    @auth
        <meta name="lobby-id" content="{{ $lobby->id }}">
        <meta name="user-id" content="{{ auth()->id() }}">
    @endauth
@endsection

@section('content')
@php
    $member = $lobby->players->contains(auth()->id());
@endphp

<div class="lobby-page-container">
    {{-- ───────────── Lobby‑info column ───────────── --}}
    <div class="lobby-info-section">
        {{-- Header --}}
        <div class="lobby-header">
            <h1>{{ $lobby->game_name }}</h1>
            <div class="host-info">
                <div class="host-avatar">
                    @if($lobby->user->photo)
                        <img src="{{ asset('storage/' . $lobby->user->photo) }}" alt="{{ $lobby->user->name }}" class="avatar-img">
                    @else
                        <div class="avatar-placeholder">{{ strtoupper(substr($lobby->user->name, 0, 1)) }}</div>
                    @endif
                </div>
                <span class="host-name">Host: {{ $lobby->user->name }}</span>
            </div>
        </div>

        {{-- Details --}}
        <div class="lobby-details">
            <div class="detail-item"><span class="detail-label">Skill Level:</span> <span>{{ $lobby->skill_level }}</span></div>
            <div class="detail-item"><span class="detail-label">Playstyle:</span>   <span>{{ $lobby->playstyle }}</span></div>
            <div class="detail-item"><span class="detail-label">Region:</span>      <span>{{ $lobby->region }}</span></div>
            <div class="detail-item"><span class="detail-label">Players:</span>     <span>{{ $lobby->players->count() }} / {{ $lobby->max_players }}</span></div>
        </div>

        {{-- Description --}}
        @if($lobby->description)
            <div class="lobby-description">
                <h3>Description</h3>
                <p>{{ $lobby->description }}</p>
            </div>
        @endif

        {{-- Game image --}}
        @if($lobby->photo)
            <div class="lobby-image">
                <img src="{{ asset('storage/' . $lobby->photo) }}" alt="{{ $lobby->game_name }}">
            </div>
        @endif

        {{-- Players list --}}
        <div class="lobby-players">
            <h3>Players in Lobby</h3>
            <div class="players-list">
                @foreach($lobby->players as $player)
                    <div class="player-item">
                        <div class="player-avatar">
                            @if($player->photo)
                                <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->name }}">
                            @else
                                <div class="avatar-placeholder">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                            @endif
                        </div>
                        <span class="player-name">{{ $player->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Join / Leave / Delete --}}
        <div class="lobby-actions">
            @guest
                <a href="{{ route('login') }}" class="join-btn">Login to join</a>
            @else
                @if(!$member && $lobby->players->count() < $lobby->max_players)
                    <form action="{{ route('lobby.join', $lobby) }}" method="POST">@csrf
                        <button class="join-btn">Join Lobby</button>
                    </form>
                @elseif($member && auth()->id() !== $lobby->user_id)
                    <form action="{{ route('lobby.leave', $lobby) }}" method="POST">@csrf
                        <button class="leave-btn">Leave Lobby</button>
                    </form>
                @endif

                @if(auth()->id() == $lobby->user_id)
                    <form action="{{ route('lobby.destroy', $lobby) }}" method="POST" class="delete-lobby-form">@csrf @method('DELETE')
                        <button class="delete-lobby">Delete Lobby</button>
                    </form>
                @endif
            @endguest
        </div>
    </div>

    {{-- Chat Section --}}
    <div class="chat-container">
        @include('partials.sidebar')

        <section id="chat-section" class="chat-section">
            <div class="chat-header">
                <h2>{{ $lobby->game_name }} Chat</h2>
                <div class="online-indicator"><span class="dot"></span><span class="status">Active</span></div>
            </div>

            <div id="chat-window" class="chat-window">
                <div id="messages" class="messages">
                    @foreach ($messages->reverse() as $message)
                        <div class="message {{ $message->user->id == Auth::id() ? 'outgoing' : 'incoming' }}">
                            <div class="message-avatar">
                                @if($message->user->photo)
                                    <img src="{{ asset('storage/' . $message->user->photo) }}" alt="{{ $message->user->name }}" class="avatar-img">
                                @else
                                    <div class="avatar-placeholder">{{ strtoupper(substr($message->user->name, 0, 1)) }}</div>
                                @endif
                            </div>
                            <div class="message-bubble">
                                <span class="message-sender">{{ $message->user->name }}</span>
                                <div class="message-content">{{ $message->message }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @auth
                    <div class="chat-input-container">
                        <form id="chatForm" action="{{ route('lobby.chat.send', $lobby) }}" method="POST">@csrf
                            <textarea id="message" name="message" rows="1" placeholder="Type your message…" required></textarea>
                            <button type="submit" class="send-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.5.5 0 0 0-.057.853l4.423 2.985v4.807a.5.5 0 0 0 .836.37l2.704-2.704L12.54 14.9a.5.5 0 0 0 .756-.281l2.666-13.333z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="login-prompt">
                        <p>Please <a href="{{ route('login') }}">login</a> to chat.</p>
                    </div>
                @endauth
            </div>
        </section>
    </div>
</div>

@include('partials.footer')
@endsection

@section('scripts')
<script src="{{ asset('js/lobby-chat.js') }}" defer></script>
@endsection
