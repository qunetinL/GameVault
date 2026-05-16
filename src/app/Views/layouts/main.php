<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($title ?? 'GameVault', ENT_QUOTES, 'UTF-8') ?>
    </title>
    <link rel="stylesheet" href="/css/style.css?v=3">
    <link rel="stylesheet" href="/css/ui-components.css?v=2">
    <style>
        .main-content { display: flex; flex-direction: column; }
        .main-content__body { flex: 1; }
        /* Chat page: full height, no footer */
        .page-chat { overflow: hidden; height: 100vh; }
        .page-chat .main-content { height: 100vh; overflow: hidden; }
        .page-chat .main-content__body { height: 100%; overflow: hidden; }
        .page-chat .chat-app { height: 100% !important; }
        .page-chat .chat-window { height: 100% !important; min-width: 0; }
        .page-chat .chat-messages { flex: 1; min-height: 0; overflow-y: auto; }
        .page-chat .footer { display: none; }
    </style>
    <?php if (isset($styles)):
        foreach ($styles as $style): ?>
            <link rel="stylesheet" href="<?= $style ?>">
        <?php endforeach; endif; ?>
</head>

<body class="<?= htmlspecialchars($bodyClass ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
            <?php
            $navItems = [
                ['href' => '/', 'label' => 'Accueil', 'icon' => 'home'],
                ['href' => '/dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['href' => '/profile', 'label' => 'Profil', 'icon' => 'user'],
                ['href' => '/collection', 'label' => 'Collection', 'icon' => 'gamepad'],
                ['href' => '/sessions', 'label' => 'Sessions', 'icon' => 'calendar'],
                ['href' => '/stats', 'label' => 'Statistiques', 'icon' => 'bar-chart'],
                ['href' => '/chat', 'label' => 'Chat', 'icon' => 'message'],
                ['href' => '/users', 'label' => 'Membres', 'icon' => 'users'],
            ];
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                $navItems[] = ['href' => '/admin', 'label' => 'Admin', 'icon' => 'shield'];
            }
            $icons = [
                'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
                'dashboard' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
                'gamepad' => '<rect x="2" y="6" width="20" height="12" rx="2"/><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/>',
                'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                'message' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
                'bar-chart' => '<line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>',
                'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                'user' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
                'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            ];
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            foreach ($navItems as $item):
                $active = ($item['href'] === '/' ? $currentPath === '/' : strpos($currentPath, $item['href']) === 0);
                ?>
                <a href="<?= htmlspecialchars($item['href']) ?>" class="sidebar__link<?= $active ? ' active' : '' ?>"
                    <?= $active ? 'aria-current="page"' : '' ?>>
                    <span class="sidebar__link-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <?= $icons[$item['icon']] ?>
                        </svg>
                    </span>
                    <?= htmlspecialchars($item['label']) ?>
                    <?php if ($item['icon'] === 'message'): ?>
                        <span id="unread-badge" class="sidebar__badge" style="display: none;">0</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar__user">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="sidebar__user-card">
                    <div class="sidebar__avatar">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2)) ?>
                    </div>
                    <div class="sidebar__user-info" style="flex-grow: 1; min-width: 0;">
                        <div class="sidebar__user-name"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur') ?>
                        </div>
                        <a href="/logout" class="sidebar__logout"
                            style="font-size: 0.75rem; color: var(--primary); text-decoration: none; display: block; margin-top: 2px;">
                            Se déconnecter
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/login" class="btn btn--primary" style="width: 100%;">Se connecter</a>
            <?php endif; ?>
        </div>
    </aside>

    <div class="main-content">
        <div class="main-content__body">
            <?= $content ?>
        </div>

        <footer class="footer">
            <p>©
                <?= date('Y') ?> GameVault — Projet DWWM
            </p>
            <nav style="margin-top: 0.5rem; font-size: 0.8rem; display: flex; gap: 1rem; justify-content: center;">
                <a href="/privacy" style="color: var(--primary); text-decoration: none;">Politique de confidentialité</a>
                <a href="/cgu" style="color: var(--primary); text-decoration: none;">CGU</a>
                <a href="/mentions-legales" style="color: var(--primary); text-decoration: none;">Mentions légales</a>
            </nav>
        </footer>
    </div>

    <!-- Cookie consent banner (RGPD) -->
    <div id="cookie-consent-banner" style="display:none; position:fixed; bottom:0; left:0; right:0; background:rgba(20,20,30,0.97); color:#fff; padding:1rem 2rem; z-index:9999; align-items:center; justify-content:center; gap:1rem; font-size:0.9rem; flex-wrap:wrap;">
        <span>Ce site utilise des cookies essentiels pour son fonctionnement. <a href="/privacy" style="color:var(--primary);text-decoration:underline;">En savoir plus</a></span>
        <button onclick="acceptCookies()" style="background:var(--primary);color:#fff;border:none;padding:0.5rem 1.5rem;border-radius:0.5rem;cursor:pointer;font-size:0.9rem;">Accepter</button>
    </div>
    <script>
        (function() {
            if (!localStorage.getItem('cookie_consent')) {
                document.getElementById('cookie-consent-banner').style.display = 'flex';
            }
        })();
        function acceptCookies() {
            localStorage.setItem('cookie_consent', '1');
            document.getElementById('cookie-consent-banner').style.display = 'none';
        }
    </script>

    <script src="/js/main.js"></script>
    <?php if (isset($scripts)):
        foreach ($scripts as $script): ?>
            <script src="<?= $script ?>"></script>
        <?php endforeach; endif; ?>
</body>

</html>