<?php
require_once __DIR__ . '/../app/Helpers/DbHelper.php';
session_start();

$db = DbHelper::getInstance()->getConnection();

// Simulation d'utilisateur (pour la démo)
$currentUser = [
    'id' => $_GET['user_id'] ?? 1,
    'username' => ($_GET['user_id'] ?? 1) == 1 ? 'AlexGamer' : 'SarahStream'
];

$session_id = $_GET['session_id'] ?? 1;

// Récupérer les infos de la session
$stmt = $db->prepare("SELECT * FROM sessions WHERE id = ?");
$stmt->execute([$session_id]);
$session = $stmt->fetch();

// Simulation de contacts (on pourrait les tirer de la DB aussi)
$contacts = [
    ['id' => 1, 'name' => 'AlexGamer', 'last_msg' => 'Prêt pour demain ?', 'time' => '10:30', 'online' => true],
    ['id' => 2, 'name' => 'SarahStream', 'last_msg' => 'Carrément !', 'time' => 'Hier', 'online' => true],
    ['id' => 3, 'name' => 'AdminVault', 'last_msg' => 'Bienvenue sur GameVault', 'time' => 'Lun.', 'online' => true],
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
    <style>
        /* Styles pour le vote */
        .vote-panel {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .vote-title {
            font-size: 0.9rem;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .vote-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .vote-option {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .vote-option:hover {
            background: rgba(var(--primary-rgb), 0.1);
            border-color: var(--primary-color);
        }

        .vote-option.selected {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .vote-count {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .typing-indicator {
            font-size: 0.8rem;
            font-style: italic;
            color: var(--primary-color);
            height: 20px;
            margin-bottom: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .typing-indicator.active {
            opacity: 1;
        }

        /* Animation des messages */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-bubble {
            animation: slideIn 0.3s ease-out forwards;
        }
    </style>
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
        <div class="chat-app" data-user-id="<?= $currentUser['id'] ?>" data-session-id="<?= $session_id ?>"
            data-username="<?= htmlspecialchars($currentUser['username']) ?>">

            <!-- Sidebar Contacts & Voting -->
            <aside class="chat-sidebar">
                <div class="chat-sidebar__header">
                    <h2>Session Info</h2>
                </div>

                <div style="padding: 10px 20px;">
                    <div class="vote-panel">
                        <div class="vote-title">
                            <span>🗳️ Vote pour le jeu</span>
                        </div>
                        <div class="vote-options" id="vote-options">
                            <!-- Dynamically loaded -->
                            <p style="font-size: 0.8rem; opacity: 0.5;">Chargement des votes...</p>
                        </div>
                    </div>

                    <h3>Contacts</h3>
                </div>

                <div class="chat-contacts">
                    <?php foreach ($contacts as $contact): ?>
                        <button
                            class="contact-item <?= $contact['id'] == ($currentUser['id'] == 1 ? 2 : 1) ? 'active' : '' ?>">
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
                            <h3><?= htmlspecialchars($session['title'] ?? 'Session Chat') ?></h3>
                            <span class="status-text">En ligne • <span id="participant-count">2</span>
                                participants</span>
                        </div>
                    </div>
                </header>

                <div class="chat-messages" id="chat-messages">
                    <!-- Messages will be loaded here -->
                </div>

                <div class="chat-footer-wrap">
                    <div class="typing-indicator" id="typing-indicator">Quelqu'un écrit...</div>
                    <footer class="chat-input-area">
                        <button class="btn-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path
                                    d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                            </svg></button>
                        <input type="text" class="chat-input" id="chat-input" placeholder="Écrivez votre message...">
                        <button class="btn btn--primary btn--icon-only" id="send-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                        </button>
                    </footer>
                </div>
            </main>
        </div>
    </div>

    <script src="/js/main.js"></script>
    <script src="/js/chat.js"></script>
</body>

</html>