/**
 * chat.js - Logique de chat en temps réel (Polling) et système de vote
 */

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', () => {
    const chatApp = document.querySelector('.chat-app');
    if (!chatApp) return;

    const userId = chatApp.dataset.userId;
    const sessionId = chatApp.dataset.sessionId;
    const username = chatApp.dataset.username;

    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');
    const voteOptions = document.getElementById('vote-options');
    const typingIndicator = document.getElementById('typing-indicator');

    let lastMessageId = 0;
    let isTyping = false;
    let typingTimeout;
    let isTabVisible = true;

    // --- VISIBILITY API (réduire le polling quand l'onglet est inactif) ---

    document.addEventListener('visibilitychange', () => {
        isTabVisible = !document.hidden;
    });

    // --- MESSAGES ---

    const fetchMessages = async () => {
        if (!isTabVisible) return; // Ne pas poll si l'onglet est inactif

        try {
            const response = await fetch(`/api/messages/${sessionId}?last_id=${lastMessageId}`);
            if (!response.ok) return;
            const messages = await response.json();

            messages.forEach(msg => {
                appendMessage(msg);
                lastMessageId = Math.max(lastMessageId, msg.id);
            });

            if (messages.length > 0) {
                scrollToBottom();
            }
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    };

    const appendMessage = (msg) => {
        const isMe = msg.sender_id == userId;
        const msgDiv = document.createElement('div');
        msgDiv.className = `message-bubble ${isMe ? 'sender' : 'recipient'}`;

        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        msgDiv.innerHTML = `
            ${!isMe ? `<span style="font-size: 0.7rem; display: block; opacity: 0.7; margin-bottom: 2px;">${escapeHtml(msg.username)}</span>` : ''}
            <p></p>
            <span class="message-time">${time}</span>
        `;
        msgDiv.querySelector('p').textContent = msg.content;
        chatMessages.appendChild(msgDiv);
    };

    const sendMessage = async () => {
        const content = chatInput.value.trim();
        if (!content) return;

        chatInput.value = '';
        stopTyping();

        try {
            await fetch('/api/messages', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    sender_id: userId,
                    content: content,
                    csrf_token: document.querySelector('input[name="csrf_token"]')?.value || ''
                })
            });
            // Récupérer le message immédiatement après l'envoi
            fetchMessages();
        } catch (error) {
            console.error('Error sending message:', error);
        }
    };

    const scrollToBottom = () => {
        // Ne scroller que si l'utilisateur est déjà proche du bas
        const threshold = 150;
        const isNearBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < threshold;
        if (isNearBottom) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    };

    // --- VOTES ---

    const fetchVotes = async () => {
        if (!isTabVisible) return;

        try {
            const response = await fetch(`/session/vote?session_id=${sessionId}`);
            if (!response.ok) return;
            const votes = await response.json();

            renderVotes(votes);
        } catch (error) {
            console.error('Error fetching votes:', error);
        }
    };

    const renderVotes = (votes) => {
        voteOptions.innerHTML = '';
        votes.forEach(vote => {
            const div = document.createElement('div');
            div.className = 'vote-option';
            div.innerHTML = `
                <span>${escapeHtml(vote.title)}</span>
                <span class="vote-count">${vote.vote_count} vote(s)</span>
            `;
            div.onclick = () => castVote(vote.game_id);
            voteOptions.appendChild(div);
        });
    };

    const castVote = async (gameId) => {
        try {
            await fetch('/session/vote', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    user_id: userId,
                    game_id: gameId
                })
            });
            fetchVotes();
        } catch (error) {
            console.error('Error casting vote:', error);
        }
    };

    // --- TYPING INDICATOR ---

    const updateTypingStatus = async (status) => {
        try {
            const response = await fetch(`/api/typing?session_id=${sessionId}&user_id=${userId}&typing=${status ? 1 : 0}`);
            const data = await response.json();

            if (data.typing_count > 0) {
                typingIndicator.classList.add('active');
                typingIndicator.textContent = data.typing_count > 1
                    ? `${data.typing_count} personnes écrivent...`
                    : `Quelqu'un écrit...`;
            } else {
                typingIndicator.classList.remove('active');
            }
        } catch (error) {
            console.log('Typing API error');
        }
    };

    let typingDebounceTimeout;
    const handleTyping = () => {
        // Debounce : n'envoyer le statut "typing" qu'une fois toutes les 2s
        if (!isTyping) {
            isTyping = true;
            updateTypingStatus(true);
        }
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(stopTyping, 3000);
    };

    const stopTyping = () => {
        if (isTyping) {
            isTyping = false;
            updateTypingStatus(false);
        }
    };

    // --- EVENTS ---

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
        else handleTyping();
    });

    // Initial load
    fetchMessages();
    fetchVotes();

    // Polling intervals (réduits quand l'onglet est inactif via isTabVisible)
    setInterval(fetchMessages, 3000);  // Poll messages every 3s (était 2s)
    setInterval(fetchVotes, 10000);    // Poll votes every 10s (était 5s)
    setInterval(() => {
        if (isTabVisible) updateTypingStatus(isTyping);
    }, 4000);
});
