@extends('layouts.app')

@section('title', 'Globāla tērzēšana')

@section('content')
@include('partials.header')
@auth
<meta name="user-id" content="{{ Auth::id() }}">
<meta name="user-name" content="{{ Auth::user()->name }}">
<meta name="user-photo" content="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : '' }}">

@endauth
<div class="chat-container">
    @include('partials.sidebar')
    
    <section id="chat-section" class="chat-section">
        <div class="chat-header">
            <h2>Globāla tērzēšana</h2>
            <div class="online-indicator">
                <span class="dot"></span>
                <span class="status">Aktīvs</span>
            </div>
        </div>

        <div id="chat-window" class="chat-window">
            <div id="messages" class="messages">
                @foreach ($messages->reverse() as $message)
                    <div class="message {{ $message->user->id == Auth::id() ? 'outgoing' : 'incoming' }}">
                        <div class="message-avatar">
                            @if($message->user->photo)
                                <img src="{{ asset('storage/' . $message->user->photo) }}" alt="{{ $message->user->name }}" class="avatar-img">
                            @else
                                {{ strtoupper(substr($message->user->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="message-bubble">
                            <div class="message-info">
                                <span class="message-sender">{{ $message->user->name }}</span>
                            </div>
                            <div class="message-content">{{ $message->content }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            @auth
            <div class="chat-input-container">
                <form id="chatForm" action="{{ route('chat.send') }}" method="POST">
                    @csrf
                    <textarea id="message" name="message" rows="1" placeholder="Ierakstiet savu ziņu..." required></textarea>
                    <div class="chat-actions">
                        <button type="submit" class="send-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.5.5 0 0 0-.057.853l4.423 2.985v4.807a.5.5 0 0 0 .836.37l2.704-2.704L12.54 14.9a.5.5 0 0 0 .756-.281l2.666-13.333z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            @else
                <div class="login-prompt">
                    <p>Lūdzu <a href="{{ route('login') }}">ieiet</a> lai sazinātos.</p>
                </div>
            @endauth
        </div>
    </section>
</div>

@include('partials.footer')
@endsection

@section('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script>
    // Pusher configuration
    window.pusherConfig = {
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        forceTLS: false
    };

    // Initialize Echo
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: window.pusherConfig.key,
        cluster: window.pusherConfig.cluster,
        forceTLS: false,
        enabledTransports: ['ws', 'wss']
    });
</script>
<script src="{{ asset('js/chat.js') }}" defer></script>
<script>
    // Auto-resize textarea as user types
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('message');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
                
                // Reset height if empty
                if (this.value === '') {
                    this.style.height = '';
                }
            });
            
            // Scroll to bottom of chat
            const messagesContainer = document.getElementById('messages');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        }
    });
</script>
@endsection

