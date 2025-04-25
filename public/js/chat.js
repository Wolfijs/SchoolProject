document.addEventListener('DOMContentLoaded', function () {
    Pusher.logToConsole = true;
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

    if (typeof Pusher !== 'undefined') {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: window.pusherConfig.key,
            cluster: window.pusherConfig.cluster,
            forceTLS: window.pusherConfig.forceTLS
        });
        
        // Listen for new messages
        window.Echo.channel('chat')
            .listen('MessageSent', (e) => {
                console.log('New message received:', e);
                if (e && e.message) {
                    // Only add the message to UI if it wasn't sent by current user
                    if (e.message.user_id != currentUserId) {
                        const userName = e.message.username;
                        // Get user photo from the message data - handle both direct photo url or storage path
                        let userPhoto = e.message.user_photo || null;
                        
                        // If the photo path doesn't start with http, assume it's a storage path and convert it
                        if (userPhoto && !userPhoto.startsWith('http')) {
                            // Check if it already has /storage/ prefix
                            if (!userPhoto.startsWith('/storage/')) {
                                userPhoto = `/storage/${userPhoto}`;
                            }
                        }
                        
                        const messageContent = e.message.content || 'No content';
                        const timestamp = e.message.created_at;
                        const userId = e.message.user_id;
                        
                        const newMessage = createMessageElement(userId, userName, userPhoto, messageContent, timestamp);
                        messagesContainer.appendChild(newMessage);
                        scrollToBottom();
                    }
                } else {
                    console.error('Received message does not have the expected structure:', e);
                }
            });
    } else {
        console.error('Pusher is not defined');
    }
});