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















    document.addEventListener('DOMContentLoaded', function () {
    Pusher.logToConsole = true;
    
    // Initialize Echo if not already initialized
    if (typeof window.Echo === 'undefined') {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: process.env.MIX_PUSHER_APP_KEY,
            cluster: process.env.MIX_PUSHER_APP_CLUSTER,
            forceTLS: true
        });
    }
    
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('message');
    const messagesContainer = document.getElementById('messages');
    
    // Auto-resize textarea as user types
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            
            // Reset height if empty
            if (this.value === '') {
                this.style.height = '';
            }
        });
    }

    // Get current user information
    const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
    const currentUserName = document.querySelector('meta[name="user-name"]')?.getAttribute('content');
    // Get current user photo if available
    const currentUserPhoto = document.querySelector('meta[name="user-photo"]')?.getAttribute('content');
    
    function createMessageElement(userId, userName, userPhoto, messageContent, timestamp = null) {
        // Format timestamp if provided, otherwise use current time
        const formattedTime = timestamp ? new Date(timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 
                                        new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        // Check if message is from current user
        const isCurrentUser = userId == currentUserId;
        const messageClass = isCurrentUser ? 'outgoing' : 'incoming';
        
        // Create avatar content
        let avatarContent;
        if (userPhoto) {
            avatarContent = `<img src="${userPhoto}" alt="${userName}" class="avatar-img">`;
        } else {
            avatarContent = `<div class="avatar-text">${userName.charAt(0).toUpperCase()}</div>`;
        }
        
        const newMessageDiv = document.createElement('div');
        newMessageDiv.classList.add('message', messageClass);
        
        newMessageDiv.innerHTML = `
            <div class="message-avatar">
                ${avatarContent}
            </div>
            <div class="message-bubble">
                <div class="message-info">
                    <span class="message-sender">${userName}</span>
                </div>
                <div class="message-content">${messageContent}</div>
            </div>
        `;
        
        return newMessageDiv;
    }

    chatForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const message = messageInput.value.trim();

        if (message === '') {
            console.warn('Message cannot be empty');
            return;
        }
        
        // Add message to UI immediately (optimistic UI update)
        if (currentUserId && currentUserName) {
            const newMessage = createMessageElement(currentUserId, currentUserName, currentUserPhoto, message);
            messagesContainer.appendChild(newMessage);
            messageInput.value = ''; // Clear input
            messageInput.style.height = ''; // Reset height
            scrollToBottom(); // Scroll to bottom
        }

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                message: message,
                user_photo: currentUserPhoto // Send the photo URL with the message
            })
        })        
        .then(response => {
            if (!response.ok) {
                return response.text().then(errorBody => {
                    console.error('Response not OK:', response.status, errorBody);
                    throw new Error('Failed to send message. Status: ' + response.status);
                });
            }
            return response.json();
        })
        .then(data => {
            // Message already shown in UI (optimistic update)
            console.log('Message sent successfully:', data);
        })
        .catch(error => {
            console.error('Error sending message:', error);
            // Could add code here to show an error state or retry option
        });
    });

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Initial scroll to bottom when page loads
    scrollToBottom();

    // Check if Echo is available globally
    if (typeof window.Echo !== 'undefined') {
        // Listen for new messages
        window.Echo.channel('chat')
            .listen('MessageSent', (e) => {
                console.log('New message received:', e);
                if (e && e.message) {
                    // Only add the message to UI if it wasn't sent by current user
                    if (e.message.user_id != currentUserId) {
                        const userName = e.message.username;
                        let userPhoto = e.message.user_photo || null;
                        
                        // Handle photo path
                        if (userPhoto && !userPhoto.startsWith('http')) {
                            userPhoto = userPhoto.startsWith('/storage/') ? userPhoto : `/storage/${userPhoto}`;
                        }
                        
                        const messageContent = e.message.content || 'No content';
                        const timestamp = e.message.created_at;
                        const userId = e.message.user_id;
                        
                        const newMessage = createMessageElement(userId, userName, userPhoto, messageContent, timestamp);
                        messagesContainer.appendChild(newMessage);
                        scrollToBottom();
                    }
                }
            });
    } else {
        console.error('Echo is not defined');
    }
});
</script>
@endsection

