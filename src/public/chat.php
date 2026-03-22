<?php
// Simulation de contacts
$contacts = [
    ['id' => 1, 'name' => 'SoulsMaster', 'last_msg' => 'On se fait une session demain ?', 'time' => '10:30', 'online' => true],
    ['id' => 2, 'name' => 'SoloPlayer', 'last_msg' => 'Merci pour le coup de main !', 'time' => 'Hier', 'online' => false],
    ['id' => 3, 'name' => 'NightWanderer', 'last_msg' => 'Ton build Elden Ring est top.', 'time' => 'Lun.', 'online' => true],
];

// Navigation
$navItems = [
    ['href' => '/', 'label' => 'Accueil', 'icon' => 'home'],
    ['href' => '/dashboard.php', 'label' => 'Dashboard', 'icon' => 'dashboard'],
    ['href' => '/collection.php', 'label' => 'Collection', 'icon' => 'gamepad'],
    ['href' => '/sessions.php', 'label' => 'Sessions', 'icon' => 'calendar'],
    ['href' => '/chat.php', 'label' => 'Chat', 'icon' => 'message'],
    ['href' => '/admin.php', 'label' => 'Admin', 'icon' => 'shield'],
];

$icons = [
    'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
    'dashboard' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
    'gamepad' => '<rect x="2" y="6" width="20" height="12" rx="2"/><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/>',
    'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
    'message' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
    'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
];
$currentPath = "/chat.php";
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie — GameVault</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="body-fixed">

    <aside class="sidebar" role="navigation" aria-label="Navigation principale">
        <a href="/" class="sidebar__logo" aria-label="GameVault — Accueil">
            <div class="sidebar__logo-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="6" width="20" height="12" rx="2" />
                    <path d="M12 12h.01M8 12h.01M16 12h.01" />
                    <path d="M6 9v6M18 9v6" />
                </svg>
            </div>
            <span class="sidebar__logo-text">GameVault</span>
        </a>
        <nav class="sidebar__nav">
            <?php foreach ($navItems as $item):
                $active = ($item['href'] === $currentPath);
                ?>
                <a href="<?= htmlspecialchars($item['href']) ?>" class="sidebar__link<?= $active ? ' active' : '' ?>">
                    <span class="sidebar__link-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <?= $icons[$item['icon']] ?>
                        </svg>
                    </span>
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <header class="mobile-header">
        <a href="/" class="mobile-header__logo">
            <div class="mobile-header__logo-icon">🎮</div>
            <span class="mobile-header__logo-text">GameVault</span>
        </a>
        <button class="mobile-header__btn" id="hamburger-btn">☰</button>
    </header>

    <div class="main-content chat-page-wrapper">
        <div class="chat-app">

            <!-- Sidebar Contacts -->
            <aside class="chat-sidebar">
                <div class="chat-sidebar__header">
                    <h2>Messages</h2>
                    <button class="btn-icon" title="Nouveau message">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </button>
                </div>

                <div class="chat-contacts">
                    <?php foreach ($contacts as $contact): ?>
                        <button class="contact-item <?= $contact['id'] == 1 ? 'active' : '' ?>">
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
                            <h3>SoulsMaster</h3>
                            <span class="status-text">En ligne</span>
                        </div>
                    </div>
                    <div class="chat-header__actions">
                        <button class="btn-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg></button>
                        <button class="btn-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="12" cy="5" r="1" />
                                <circle cx="12" cy="19" r="1" />
                            </svg></button>
                    </div>
                </header>

                <div class="chat-messages">
                    <div class="message-bubble recipient">
                        <p>Salut ! Tu as vu pour la session de demain ?</p>
                        <span class="message-time">10:15</span>
                    </div>
                    <div class="message-bubble sender">
                        <p>Oui j'ai vu ! Je serai là à l'heure.</p>
                        <span class="message-time">10:20</span>
                    </div>
                    <div class="message-bubble recipient">
                        <p>Super, on se fait une session Elden Ring ?</p>
                        <span class="message-time">10:22</span>
                    </div>
                    <div class="message-bubble sender">
                        <p>On se fait une session demain ?</p>
                        <span class="message-time">10:30</span>
                    </div>
                </div>

                <footer class="chat-input-area">
                    <button class="btn-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path
                                d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                        </svg></button>
                    <input type="text" class="chat-input" placeholder="Écrivez votre message...">
                    <button class="btn btn--primary btn--icon-only">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                    </button>
                </footer>
            </main>
        </div>
    </div>

    <script src="/js/main.js"></script>
</body>

</html>