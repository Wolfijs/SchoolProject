// Remove the imports since we're loading Echo globally
document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('message');
    const messagesContainer = document.getElementById('messages');
    const lobbyId = document.querySelector('meta[name="lobby-id"]')?.content;
    const userId = document.querySelector('meta[name="user-id"]')?.content;

    if (!lobbyId) {
        console.error('No lobby ID found');
        return;
    }

    // Scroll to bottom on load
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Initialize Echo with auth configuration
    window.Echo.private(`lobby.${lobbyId}`)
        .listen('LobbyMessageSent', (event) => {
            console.log('Received message:', event);
            const msg = event.message;
            // Only show message if it's not from the current user
            if (msg.user.id != userId) {
                const html = renderMessageHTML(msg, false);
                messagesContainer.insertAdjacentHTML('beforeend', html);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        })
        .error((error) => {
            console.error('Echo error:', error);
        });

    // Handle chat message sending
    if (chatForm) {
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text) return;

            try {
                const response = await fetch(chatForm.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ message: text }),
                });

                if (response.ok) {
                    const data = await response.json();
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    
                    // Add the message to the UI immediately for the sender
                    if (data.message) {
                        const html = renderMessageHTML(data.message, true);
                        messagesContainer.insertAdjacentHTML('beforeend', html);
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                } else {
                    console.error('Failed to send message:', await response.text());
                }
            } catch (error) {
                console.error('Error sending message:', error);
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
            ? `<img src="${message.user.photo}" alt="${message.user.name}" class="avatar-img">`
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
