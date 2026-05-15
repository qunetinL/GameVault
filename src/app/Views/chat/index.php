<input type="hidden" name="csrf_token" value="<?= \App\Helpers\CsrfHelper::getToken() ?>">
<div class="chat-app" data-user-id="<?= htmlspecialchars($currentUser['id'], ENT_QUOTES, 'UTF-8') ?>" data-session-id="<?= htmlspecialchars($session_id, ENT_QUOTES, 'UTF-8') ?>"
    data-username="<?= htmlspecialchars($currentUser['username']) ?>">

    <!-- Sidebar Contacts & Voting -->
    <aside class="chat-sidebar">
        <div class="chat-sidebar__header">
            <h2>Session Info</h2>
        </div>

        <div style="padding: 10px 20px;">
            <?php if (($session['max_players'] ?? 0) >= 3 && empty($session['selected_game_id'])): ?>
            <div class="vote-panel">
                <div class="vote-title">
                    <span>🗳️ Vote pour le jeu</span>
                </div>
                <div class="vote-options" id="vote-options">
                    <p style="font-size: 0.8rem; opacity: 0.5;">Chargement des votes...</p>
                </div>
            </div>
            <?php endif; ?>
            <h3>Contacts</h3>
        </div>

        <div class="chat-contacts">
            <?php foreach ($contacts as $contact): ?>
                <button class="contact-item <?= $contact['id'] == $session_id ? 'active' : '' ?>" data-session-id="<?= htmlspecialchars($contact['id']) ?>">
                    <div class="contact-avatar">
                        <span class="avatar-init">
                            <?= substr($contact['name'], 0, 1) ?>
                        </span>
                        <?php if ($contact['online']): ?><span class="online-indicator"></span>
                        <?php endif; ?>
                    </div>
                    <div class="contact-info">
                        <div class="contact-header">
                            <span class="contact-name">
                                <?= htmlspecialchars($contact['name']) ?>
                            </span>
                            <span class="contact-time">
                                <?= $contact['time'] ?>
                            </span>
                        </div>
                        <p class="contact-preview">
                            <?= htmlspecialchars($contact['last_msg']) ?>
                        </p>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>
    </aside>

    <!-- Main Chat Area -->
    <main class="chat-window">
        <header class="chat-header">
            <div class="chat-header__user">
                <div class="avatar--sm"></div>
                <div>
                    <h3>
                        <?= htmlspecialchars($session['title'] ?? 'Session Chat') ?>
                    </h3>
                    <span class="status-text">En ligne • <span id="participant-count">2</span> participants</span>
                </div>
            </div>
        </header>

        <div class="chat-messages" id="chat-messages">
            <!-- Messages chargés via JS -->
        </div>

        <div class="chat-footer-wrap">
            <div class="typing-indicator" id="typing-indicator">Quelqu'un écrit...</div>
            <footer class="chat-input-area">
                <input type="text" class="chat-input" id="chat-input" placeholder="Écrivez votre message...">
                <button class="btn btn--primary btn--icon-only" id="send-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13" />
                        <polygon points="22 2 15 22 11 13 2 9 22 2" />
                    </svg>
                </button>
            </footer>
        </div>
    </main>
</div>