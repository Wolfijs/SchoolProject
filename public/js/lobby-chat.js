import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    authEndpoint: "/broadcasting/auth",
});

document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('message');
    const messagesContainer = document.getElementById('messages');
    const lobbyId = document.querySelector('meta[name="lobby-id"]')?.content;
    const userId = document.querySelector('meta[name="user-id"]')?.content;

    // Scroll to bottom on load
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Subscribe to private lobby channel
    if (lobbyId) {
        Echo.private(`lobby.${lobbyId}`)
            .listen('LobbyMessageSent', (event) => {
                const msg = event.message;
                const isOwn = msg.user.id == userId;
                const html = renderMessageHTML(msg, isOwn);
                messagesContainer.insertAdjacentHTML('beforeend', html);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            });
    }

    // Handle chat message sending
    if (chatForm) {
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text) return;

            const response = await fetch(chatForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ message: text }),
            });

            if (response.ok) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
            }
        });
    }

    // Textarea autoresize
    if (messageInput) {
        messageInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
            if (this.value === '') this.style.height = '';
        });
    }

    // Message renderer
    function renderMessageHTML(message, isOwn) {
        const avatar = message.user.photo
            ? `<img src="/storage/${message.user.photo}" alt="${message.user.name}" class="avatar-img">`
            : `<div class="avatar-placeholder">${message.user.name.charAt(0).toUpperCase()}</div>`;

        return `
        <div class="message ${isOwn ? 'outgoing' : 'incoming'}">
            <div class="message-avatar">${avatar}</div>
            <div class="message-bubble">
                <span class="message-sender">${message.user.name}</span>
                <div class="message-content">${message.message}</div>
            </div>
        </div>`;
    }
});
