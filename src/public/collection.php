<?php
// Simulation de données de jeux
$games = [
    ['id' => '1', 'title' => 'Cyberpunk 2077', 'genre' => 'RPG', 'rating' => 4.5, 'platform' => 'PC', 'img' => '/assets/cyberpunk.jpeg', 'emoji' => '🤖'],
    ['id' => '2', 'title' => 'Elden Ring', 'genre' => 'Action RPG', 'rating' => 5.0, 'platform' => 'PS5', 'img' => '/assets/elden_ring.jpeg', 'emoji' => '⚔️'],
    ['id' => '3', 'title' => 'Valorant', 'genre' => 'FPS', 'rating' => 4.3, 'platform' => 'PC', 'img' => '/assets/valorant.jpeg', 'emoji' => '🎯'],
    ['id' => '4', 'title' => 'The Last of Us II', 'genre' => 'Action-Adventure', 'rating' => 4.8, 'platform' => 'PS4', 'img' => '/assets/tlou2.jpeg', 'emoji' => '🧟'],
    ['id' => '5', 'title' => 'Minecraft', 'genre' => 'Sandbox', 'rating' => 4.6, 'platform' => 'Multi', 'img' => '/assets/minecraft.jpeg', 'emoji' => '⛏️'],
    ['id' => '6', 'title' => 'God of War Ragnarök', 'genre' => 'Action-Adventure', 'rating' => 4.9, 'platform' => 'PS5', 'img' => '/assets/god_of_war.jpeg', 'emoji' => '🪓'],
    ['id' => '7', 'title' => 'Hades', 'genre' => 'Roguelike', 'rating' => 4.9, 'platform' => 'Switch', 'img' => '/assets/hades.jpeg', 'emoji' => '🔥'],
    ['id' => '8', 'title' => 'Spider-Man 2', 'genre' => 'Action', 'rating' => 4.7, 'platform' => 'PS5', 'img' => '/assets/spiderman2.jpeg', 'emoji' => '🕸️'],
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
    <title>🎮 Ma Collection — GameVault</title>
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

            <header class="section__header">
                <div class="section__titles">
                    <h1>Ma Collection</h1>
                    <p>Gérez vos jeux favoris et suivez votre progression.</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn--primary">Ajouter un jeu</button>
                </div>
            </header>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="search-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" class="form-input" placeholder="Rechercher un jeu...">
                </div>

                <select class="filter-select">
                    <?php foreach ($genres as $g): ?>
                        <option>
                            <?= $g ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select class="filter-select">
                    <?php foreach ($platforms as $p): ?>
                        <option>
                            <?= $p ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Collection Grid -->
            <div class="games-grid">
                <?php foreach ($games as $game): ?>
                    <article class="game-card">
                        <a href="/game.php?id=<?= $game['id'] ?>" style="display:contents">
                            <div class="game-card__cover">
                                <img src="<?= $game['img'] ?>" alt="<?= $game['title'] ?>" loading="lazy"
                                    onerror="this.style.display='none';this.closest('.game-card__cover').textContent='<?= $game['emoji'] ?>';">
                                <div class="game-card__rating-badge">
                                    <svg viewBox="0 0 24 24">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                    <?= $game['rating'] ?>
                                </div>
                            </div>
                            <div class="game-card__body">
                                <h3 class="game-card__title">
                                    <?= $game['title'] ?>
                                </h3>
                                <p class="game-card__genre">
                                    <?= $game['genre'] ?> •
                                    <?= $game['platform'] ?>
                                </p>
                            </div>
                        </a>
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