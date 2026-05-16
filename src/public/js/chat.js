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

        const storeBadges = msg.user_stores
            ? msg.user_stores.split(', ').map(s => `<span style="font-size: 0.6rem; padding: 1px 4px; border-radius: 3px; background: rgba(255,255,255,0.1); margin-left: 3px;">${escapeHtml(s)}</span>`).join('')
            : '';

        msgDiv.innerHTML = `
            ${!isMe ? `<span style="font-size: 0.7rem; display: block; opacity: 0.7; margin-bottom: 2px;"><a href="/user?id=${msg.sender_id}" style="color: inherit; text-decoration: underline; text-underline-offset: 2px;">${escapeHtml(msg.username)}</a>${storeBadges}</span>` : ''}
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
            const response = await fetch(`/api/votes/${sessionId}`);
            if (!response.ok) return;
            const votes = await response.json();

            renderVotes(votes);
        } catch (error) {
            console.error('Error fetching votes:', error);
        }
    };

    let gameOwners = {};

    const fetchSessionStores = async () => {
        if (!isTabVisible) return;
        try {
            const response = await fetch(`/api/session-stores/${sessionId}`);
            if (!response.ok) return;
            const data = await response.json();
            gameOwners = {};
            data.forEach(item => {
                gameOwners[item.game_id] = item.owners;
            });
        } catch (error) {
            console.error('Error fetching session stores:', error);
        }
    };

    const renderVotes = (votes) => {
        voteOptions.innerHTML = '';
        votes.forEach(vote => {
            const div = document.createElement('div');
            div.className = 'vote-option';

            let ownersHtml = '';
            const owners = gameOwners[vote.game_id];
            if (owners && owners.length > 0) {
                const ownerParts = owners.map(o => {
                    const stores = o.stores ? `: ${escapeHtml(o.stores)}` : '';
                    return `${escapeHtml(o.username)}${stores}`;
                });
                ownersHtml = `<div style="font-size: 0.7rem; opacity: 0.6; margin-top: 2px;">${ownerParts.join(' | ')}</div>`;
            }

            div.innerHTML = `
                <div>
                    <span>${escapeHtml(vote.title)}</span>
                    <span class="vote-count">${vote.vote_count} vote(s)</span>
                </div>
                ${ownersHtml}
            `;
            div.onclick = () => castVote(vote.game_id);
            voteOptions.appendChild(div);
        });
    };

    const castVote = async (gameId) => {
        try {
            await fetch('/api/votes', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    game_id: gameId,
                    csrf_token: document.querySelector('input[name="csrf_token"]')?.value || ''
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

    // Clic sur un contact → naviguer vers cette session de chat
    document.querySelectorAll('.contact-item').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetSessionId = btn.dataset.sessionId;
            if (targetSessionId && targetSessionId !== sessionId) {
                window.location.href = `/chat?session_id=${targetSessionId}`;
            }
        });
    });

    // Initial load
    fetchMessages();
    if (voteOptions) {
        fetchSessionStores().then(() => fetchVotes());
    }

    // Polling intervals
    setInterval(fetchMessages, 3000);
    if (voteOptions) {
        setInterval(() => fetchSessionStores().then(() => fetchVotes()), 10000);
    }
    setInterval(() => {
        if (isTabVisible) updateTypingStatus(isTyping);
    }, 4000);
});
