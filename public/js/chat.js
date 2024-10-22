document.addEventListener('DOMContentLoaded', function () {
    Pusher.logToConsole = true;
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('message');
    const messagesContainer = document.getElementById('messages');

    function createMessageElement(userName, messageContent) {
        const newMessageDiv = document.createElement('div');
        newMessageDiv.classList.add('message');
        newMessageDiv.innerHTML = `<strong>${userName}:</strong> ${messageContent}`;
        return newMessageDiv;
    }

    chatForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const message = messageInput.value.trim();

        if (message === '') {
            console.warn('Message cannot be empty');
            return;
        }

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message })
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
            // Manually update the UI with the message sent
            if (data.message) {
                const userName = data.message.username;
                messagesContainer.appendChild(createMessageElement(userName, data.message.content));
                messageInput.value = ''; // Clear input
                scrollToBottom(); // Scroll to the bottom
            } else {
                console.error('Message structure is not valid:', data);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    });

    function scrollToBottom() {
        const atBottom = messagesContainer.scrollTop + messagesContainer.clientHeight >= messagesContainer.scrollHeight;
        if (atBottom) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    if (typeof Pusher !== 'undefined') {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: window.pusherConfig.key,
            cluster: window.pusherConfig.cluster,
            forceTLS: false // Set forceTLS to true for production
        });
    
        // Listen for new messages
        window.Echo.channel('chat')
            .listen('MessageSent', (e) => {
                console.log('New message received:', e);
                if (e && e.message) {
                    const userName = e.message.username;
                    const messageContent = e.message.content || 'No content';
                    messagesContainer.appendChild(createMessageElement(userName, messageContent));
                    scrollToBottom(); // Scroll to the bottom when a new message arrives
                } else {
                    console.error('Received message does not have the expected structure:', e);
                }
            });
    } else {
        console.error('Pusher is not defined');
    }
    
    
    
});
