<?php
// Simulation de données (sera remplacé par la BDD en Phase 3)
$games = [
    '1' => [
        'title' => 'Cyberpunk 2077',
        'genre' => 'RPG / Open World',
        'rating' => 4.5,
        'platform' => 'PC, PS5, Xbox Series X',
        'img' => '/assets/cyberpunk.jpeg',
        'backdrop' => 'https://images.unsplash.com/photo-1605898962319-19443a0d883b?q=80&w=1920&auto=format&fit=crop',
        'desc' => "Cyberpunk 2077 est un RPG d'action-aventure en monde ouvert qui se déroule dans la mégapole de Night City, où vous incarnez un mercenaire cyberpunk plongé dans une lutte acharnée pour la survie. Amélioré et doté de tout nouveau contenu gratuit supplémentaire, personnalisez votre personnage et votre style de jeu en acceptant des boulots, en vous forgeant une réputation et en déverrouillant des améliorations.",
        'dev' => 'CD Projekt Red',
        'pub' => 'CD Projekt',
        'release' => '10 Déc. 2020'
    ]
];

$id = $_GET['id'] ?? '1';
$game = $games[$id] ?? $games['1'];

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
$currentPath = "/collection.php"; // Pour garder le lien collection actif
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($game['title']) ?> — GameVault
    </title>
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
        <div class="game-detail">
            <div class="game-detail__backdrop" style="background-image: url('<?= $game['backdrop'] ?>')">
                <div class="game-detail__overlay"></div>
            </div>

            <main class="game-detail__container">
                <div class="game-detail__grid">

                    <!-- Colonne Gauche -->
                    <div class="game-detail__main">
                        <nav class="breadcrumb">
                            <a href="/collection.php">Collection</a> / <span>
                                <?= htmlspecialchars($game['title']) ?>
                            </span>
                        </nav>

                        <h1 class="game-detail__title">
                            <?= htmlspecialchars($game['title']) ?>
                        </h1>
                        <div class="game-detail__badges">
                            <span class="badge badge--primary">
                                <?= htmlspecialchars($game['genre']) ?>
                            </span>
                            <span class="badge badge--rating">⭐
                                <?= $game['rating'] ?>
                            </span>
                        </div>

                        <div class="game-detail__section">
                            <h2>À propos</h2>
                            <p class="game-detail__description">
                                <?= nl2br(htmlspecialchars($game['desc'])) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Colonne Droite (Sidebar Infos) -->
                    <aside class="game-detail__sidebar">
                        <div class="game-detail__actions">
                            <button class="btn btn--primary btn--full">Ajouter à la collection</button>
                            <button class="btn btn--outline btn--full">Créer une session</button>
                        </div>

                        <div class="info-card">
                            <div class="info-item">
                                <span class="info-label">Développeur</span>
                                <span class="info-value">
                                    <?= htmlspecialchars($game['dev']) ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Éditeur</span>
                                <span class="info-value">
                                    <?= htmlspecialchars($game['pub']) ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Date de sortie</span>
                                <span class="info-value">
                                    <?= htmlspecialchars($game['release']) ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Plateformes</span>
                                <span class="info-value">
                                    <?= htmlspecialchars($game['platform']) ?>
                                </span>
                            </div>
                        </div>
                    </aside>

                </div>
            </main>
        </div>

        <footer class="footer">
            <p>©
                <?= date('Y') ?> GameVault — Projet DWWM
            </p>
        </footer>
    </div>

    <script src="/js/main.js"></script>
</body>

</html>