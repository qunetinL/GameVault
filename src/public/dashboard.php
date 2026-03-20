<?php
// Simulation de données (sera remplacé par la BDD en Phase 3)
$stats = [
    ['label' => 'Jeux Collectionnés', 'value' => '42', 'icon' => 'gamepad', 'color' => 'var(--primary)'],
    ['label' => 'Sessions à venir', 'value' => '3', 'icon' => 'calendar', 'color' => 'var(--secondary)'],
    ['label' => 'Amis en ligne', 'value' => '12', 'icon' => 'users', 'color' => '#10B981'],
];

$activities = [
    ['text' => 'Vous avez ajouté **Elden Ring** à votre collection.', 'time' => 'Il y a 2 heures'],
    ['text' => '**ProGamer123** vous a invité à une session Valorant.', 'time' => 'Il y a 5 heures'],
    ['text' => 'Session **Cyberpunk 2077** terminée.', 'time' => 'Hier'],
];

$navItems = [
    ['href' => '/',             'label' => 'Accueil',    'icon' => 'home'],
    ['href' => '/dashboard.php','label' => 'Dashboard',  'icon' => 'dashboard'],
    ['href' => '/collection.php','label'=> 'Collection', 'icon' => 'gamepad'],
    ['href' => '/sessions.php', 'label' => 'Sessions',   'icon' => 'calendar'],
    ['href' => '/chat.php',     'label' => 'Chat',       'icon' => 'message'],
    ['href' => '/admin.php',    'label' => 'Admin',      'icon' => 'shield'],
];
$icons = [
    'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
    'dashboard' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
    'gamepad' => '<rect x="2" y="6" width="20" height="12" rx="2"/><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/>',
    'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
    'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    'message' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
    'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
];
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — GameVault</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

    <!-- Sidebar & Mobile Header (Copié de index.php pour la phase statique) -->
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
                $active = ($item['href'] === '/' ? $currentPath === '/' : strpos($currentPath, $item['href']) === 0);
            ?>
            <a href="<?= htmlspecialchars($item['href']) ?>"
               class="sidebar__link<?= $active ? ' active' : '' ?>"
               <?= $active ? 'aria-current="page"' : '' ?>>
                <span class="sidebar__link-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <?= $icons[$item['icon']] ?>
                    </svg>
                </span>
                <?= htmlspecialchars($item['label']) ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar__user">
            <div class="sidebar__user-card">
                <div class="sidebar__avatar">PG</div>
                <div class="sidebar__user-name">ProGamer123</div>
            </div>
        </div>
    </aside>

    <header class="mobile-header">
        <a href="/" class="mobile-header__logo">
            <div class="mobile-header__logo-icon">🎮</div>
            <span class="mobile-header__logo-text">GameVault</span>
        </a>
        <button class="mobile-header__btn" id="hamburger-btn" aria-label="Ouvrir le menu" aria-expanded="false"
            aria-controls="mobile-nav">
            <svg id="icon-menu" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
            <svg id="icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" style="display:none">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
    </header>

    <nav id="mobile-nav" class="mobile-nav" aria-label="Menu mobile">
        <?php foreach ($navItems as $item): 
            $active = ($item['href'] === '/' ? $currentPath === '/' : strpos($currentPath, $item['href']) === 0);
        ?>
        <a href="<?= htmlspecialchars($item['href']) ?>"
           class="<?= $active ? 'active' : '' ?>">
            <span class="mobile-nav__icon" aria-hidden="true" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <?= $icons[$item['icon']] ?>
                </svg>
            </span>
            <?= htmlspecialchars($item['label']) ?>
        </a>
        <?php endforeach; ?>
    </nav>

    <div class="main-content">
        <main class="section">

            <header class="section__header" style="margin-bottom: 3rem;">
                <div class="section__titles">
                    <h1>Tableau de Bord</h1>
                    <p>Ravi de vous revoir, ProGamer123 !</p>
                </div>
                <div class="header-actions">
                    <a href="/collection.php" class="btn btn--primary">Ajouter un jeu</a>
                </div>
            </header>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                    <div class="stat-card">
                        <div class="stat-card__icon"
                            style="background: <?= $stat['color'] ?>15; color: <?= $stat['color'] ?>;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <?= $icons[$stat['icon']] ?>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-card__value">
                                <?= $stat['value'] ?>
                            </div>
                            <div class="stat-card__label">
                                <?= $stat['label'] ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Layout 2 colonnes pour Activité et Sessions -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">

                <!-- Activité récente -->
                <div>
                    <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Activité récente</h2>
                    <div class="activity-list">
                        <?php foreach ($activities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-item__dot"></div>
                                <div class="activity-content">
                                    <div class="activity-item__text">
                                        <?= str_replace('**', '<strong>', str_replace('**', '</strong>', $activity['text'])) ?>
                                    </div>
                                    <div class="activity-item__time">
                                        <?= $activity['time'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Actions rapides / Sessions à venir (compact) -->
                <div>
                    <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Prochaine session</h2>
                    <div class="session-card" style="padding: 1rem;">
                        <div class="session-card__info">
                            <h3 style="font-size: 1rem;">Valorant - Tournoi</h3>
                            <p style="font-size: 0.8125rem; color: var(--muted-fg);">Ce soir, 20:00</p>
                        </div>
                        <a href="/sessions.php" class="btn btn--outline btn--sm">Voir</a>
                    </div>
                </div>

            </div>

        </main>

        <footer class="footer">
            <p>©
                <?= date('Y') ?> GameVault — Projet DWWM
            </p>
        </footer>
    </div>

    <script src="/js/main.js"></script>
</body>

</html>