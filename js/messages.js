// messages.js

document.addEventListener('DOMContentLoaded', () => {
    const sendButton = document.getElementById('send');
    const messageInput = document.getElementById('message');
    const messagesContainer = document.getElementById('messages');

    sendButton.addEventListener('click', () => {
        const messageText = messageInput.value.trim();

        if (messageText) {
            const messageElement = document.createElement('div');
            messageElement.className = 'message sent';
            messageElement.innerHTML = `
                <p>${messageText}</p>
                <span class="timestamp">${new Date().toLocaleTimeString()}</span>
            `;

            messagesContainer.appendChild(messageElement);
            messageInput.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            // console.log('message sent');
            insertText(userid, recipient, messageText);
        }
    });

    // Optional: Handle pressing Enter to send message
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendButton.click();
        }
    });
});





