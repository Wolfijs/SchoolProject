@extends('layouts.app')

@section('title', 'Chat with Teammates')

@section('content')
@include('partials.header')
@include('partials.sidebar')

<section id="chat-section" class="chat-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Team Chat</h2>
            <p>Chat with your teammates in real-time!</p>
        </div>

        <div id="chat-window" class="chat-window">
            <div id="messages" class="messages">
                @foreach ($messages as $message)
                    <div class="message">
                        <strong>{{ $message->user->name }}:</strong> {{ $message->content }}
                    </div>
                @endforeach
            </div>
            @auth
            <div class="chat-input-container">
                <form id="chatForm" action="{{ route('chat.send') }}" method="POST">
                    @csrf
                    <textarea id="message" name="message" rows="2" class="form-control" placeholder="Type your message..." required></textarea>
                    <button type="submit" class="btn-primarys">Send</button>
                </form>
            </div>
            @else
                <p class="text-muted">Please <a href="{{ route('login') }}">login</a> to participate in the chat.</p>
            @endauth
        </div>
    </div>
</section>

@include('partials.footer')

@endsection

@section('scripts')
<script src="{{ asset('js/chat.js') }}" defer></script>
@endsection
