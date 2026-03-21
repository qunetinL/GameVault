<?php
// Simulation de données de sessions
$sessions = [
    [
        'game' => 'Valorant',
        'title' => 'Tournoi Amical 5v5',
        'date' => 'Ce soir, 20:00',
        'players' => '3/5',
        'level' => 'Intermédiaire',
        'host' => 'ProGamer123',
        'img' => '/assets/valorant.jpeg'
    ],
    [
        'game' => 'Cyberpunk 2077',
        'title' => 'Découverte DLC Phantom Liberty',
        'date' => 'Demain, 14:00',
        'players' => '1/1',
        'level' => 'Tous niveaux',
        'host' => 'SoloPlayer',
        'img' => '/assets/cyberpunk.jpeg'
    ],
    [
        'game' => 'Elden Ring',
        'title' => 'Co-op Boss Malenia',
        'date' => '22 Mars, 21:00',
        'players' => '2/3',
        'level' => 'Avancé',
        'host' => 'SoulsMaster',
        'img' => '/assets/elden_ring.jpeg'
    ]
];

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
$currentPath = "/sessions.php";
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions Gaming — GameVault</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

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

    <div class="main-content">
        <main class="section">
            <header class="section__header">
                <div class="section__titles">
                    <h1>Sessions Gaming</h1>
                    <p>Retrouvez vos amis ou rejoignez de nouveaux joueurs.</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn--primary">Créer une session</button>
                </div>
            </header>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="search-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" class="form-input" placeholder="Rechercher une session...">
                </div>
                <select class="filter-select">
                    <option>Tous les jeux</option>
                    <option>Valorant</option>
                    <option>Elden Ring</option>
                </select>
                <input type="date" class="filter-select" style="min-width: 150px;">
            </div>

            <!-- Sessions Grid -->
            <div class="sessions-grid">
                <?php foreach ($sessions as $session): ?>
                    <article class="session-card">
                        <div class="session-card__image" style="background-image: url('<?= $session['img'] ?>')">
                            <div class="session-card__badge">
                                <?= htmlspecialchars($session['game']) ?>
                            </div>
                        </div>
                        <div class="session-card__body">
                            <h3 class="session-card__title">
                                <?= htmlspecialchars($session['title']) ?>
                            </h3>
                            <div class="session-card__meta">
                                <span>📅
                                    <?= $session['date'] ?>
                                </span>
                                <span>👤
                                    <?= $session['players'] ?> joueurs
                                </span>
                            </div>
                            <div class="session-card__footer">
                                <div class="session-card__host">
                                    <div class="avatar--sm"></div>
                                    <span>
                                        <?= htmlspecialchars($session['host']) ?>
                                    </span>
                                </div>
                                <button class="btn btn--outline btn--sm">Rejoindre</button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
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