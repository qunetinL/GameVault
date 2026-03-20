<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="GameVault — Gérez votre collection de jeux vidéo et organisez des sessions gaming avec vos amis.">
    <title>Accueil — GameVault</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

    <!-- ═══════════════════════════════════════════════
     SIDEBAR (desktop)
═══════════════════════════════════════════════════ -->
    <aside class="sidebar" role="navigation" aria-label="Navigation principale">

        <!-- Logo -->
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

        <!-- Liens nav -->
        <nav class="sidebar__nav">
            <?php
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
                'gamepad' => '<line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><rect x="2" y="6" width="20" height="12" rx="2"/>',
                'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                'message' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
                'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
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
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Profil utilisateur -->
        <div class="sidebar__user">
            <div class="sidebar__user-card">
                <div class="sidebar__avatar" aria-hidden="true">PG</div>
                <div style="min-width:0">
                    <div class="sidebar__user-name">ProGamer123</div>
                    <div class="sidebar__user-status">● En ligne</div>
                </div>
            </div>
        </div>

    </aside>

    <!-- ═══════════════════════════════════════════════
     HEADER MOBILE
═══════════════════════════════════════════════════ -->
    <header class="mobile-header" role="banner">
        <a href="/" class="mobile-header__logo" aria-label="GameVault">
            <div class="mobile-header__logo-icon" aria-hidden="true">🎮</div>
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
            <a href="<?= htmlspecialchars($item['href']) ?>" class="<?= $active ? 'active' : '' ?>">
                <span class="mobile-nav__icon" aria-hidden="true"
                    style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <?= $icons[$item['icon']] ?>
                    </svg>
                </span>
                <?= htmlspecialchars($item['label']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- ═══════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════════ -->
    <div class="main-content">
        <main id="main-content">

            <!-- ═══════════════════════════════════════════
         HERO
    ════════════════════════════════════════════════ -->
            <section class="hero" aria-labelledby="hero-title">
                <div class="hero__inner">

                    <!-- Colonne gauche : texte -->
                    <div class="hero__content">

                        <div class="hero__badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                            </svg>
                            Plateforme Gaming Premium
                        </div>

                        <h1 class="hero__title" id="hero-title">
                            Rejoignez la
                            <span class="hero__title-gradient">Guilde Ultime</span>
                        </h1>

                        <p class="hero__desc">
                            Connectez-vous avec des milliers de joueurs, organisez des sessions épiques,
                            et construisez votre collection de jeux. L'aventure commence ici.
                        </p>

                        <div class="hero__actions">
                            <a href="/register.php" class="btn btn--primary">
                                Rejoindre la Guilde
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg>
                            </a>
                            <a href="/collection.php" class="btn btn--outline">
                                Explorer les Jeux
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="hero__stats" aria-label="Statistiques de la plateforme">
                            <div>
                                <div class="hero__stat-value hero__stat-value--primary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    50K+
                                </div>
                                <div class="hero__stat-label">Joueurs Actifs</div>
                            </div>
                            <div>
                                <div class="hero__stat-value hero__stat-value--secondary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                    10K+
                                </div>
                                <div class="hero__stat-label">Jeux Disponibles</div>
                            </div>
                            <div>
                                <div class="hero__stat-value hero__stat-value--primary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                    </svg>
                                    24/7
                                </div>
                                <div class="hero__stat-label">Sessions Live</div>
                            </div>
                        </div>

                    </div>

                    <!-- Colonne droite : image gaming -->
                    <div class="hero__visual" aria-hidden="true">
                        <div class="hero__image-wrap">
                            <!-- Image Unsplash gaming setup (même URL que le mockup) -->
                            <img class="hero__image"
                                src="https://images.unsplash.com/photo-1642791401714-a0d4e02d4bb6?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxnYW1pbmclMjBkZXNrJTIwc2V0dXAlMjBuZW9uJTIwbGlnaHRzfGVufDF8fHx8MTc3MzI5NDYwNXww&ixlib=rb-4.1.0&q=80&w=1080"
                                alt="Gaming setup avec éclairage néon"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <!-- Fallback si pas de connexion -->
                            <div class="hero__image-placeholder" style="display:none">🎮</div>
                            <div class="hero__image-overlay"></div>
                        </div>

                        <!-- Floating card -->
                        <div class="hero__floating-card">
                            <div class="hero__floating-icon">🏆</div>
                            <div>
                                <div class="hero__floating-label">Dernière Victoire</div>
                                <div class="hero__floating-value">Tournoi Valorant #23</div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- ═══════════════════════════════════════════
         JEUX POPULAIRES
    ════════════════════════════════════════════════ -->
            <section class="section" aria-labelledby="games-title">

                <div class="section__header">
                    <div class="section__titles">
                        <h2 id="games-title">Jeux Populaires</h2>
                        <p>Les plus joués par la communauté</p>
                    </div>
                    <a href="/collection.php" class="section__see-all">
                        Voir tout
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                </div>

                <!-- TODO Jour 15 : remplacer par fetch('/api/games/popular') en AJAX -->
                <div class="games-grid" role="list">
                    <?php
                    $popularGames = [
                        ['id' => '1', 'title' => 'Cyberpunk 2077', 'genre' => 'RPG', 'rating' => 4.5, 'playTime' => 120, 'img' => '/assets/cyberpunk.jpeg', 'emoji' => '🤖'],
                        ['id' => '2', 'title' => 'Elden Ring', 'genre' => 'Action RPG', 'rating' => 5.0, 'playTime' => 95, 'img' => '/assets/elden_ring.jpeg', 'emoji' => '⚔️'],
                        ['id' => '3', 'title' => 'Valorant', 'genre' => 'FPS', 'rating' => 4.3, 'playTime' => 250, 'img' => '/assets/valorant.jpeg', 'emoji' => '🎯'],
                        ['id' => '4', 'title' => 'The Last of Us II', 'genre' => 'Action-Adventure', 'rating' => 4.8, 'playTime' => 35, 'img' => '/assets/tlou2.jpeg', 'emoji' => '🧟'],
                        ['id' => '5', 'title' => 'Minecraft', 'genre' => 'Sandbox', 'rating' => 4.6, 'playTime' => 450, 'img' => '/assets/minecraft.jpeg', 'emoji' => '⛏️'],
                        ['id' => '6', 'title' => 'God of War Ragnarök', 'genre' => 'Action-Adventure', 'rating' => 4.9, 'playTime' => 45, 'img' => '/assets/god_of_war.jpeg', 'emoji' => '🪓'],
                    ];
                    foreach ($popularGames as $game):
                        ?>
                        <article class="game-card" role="listitem">
                            <a href="/game.php?id=<?= htmlspecialchars($game['id']) ?>" style="display:contents">
                                <div class="game-card__cover">
                                    <img src="<?= htmlspecialchars($game['img']) ?>"
                                        alt="<?= htmlspecialchars($game['title']) ?>" loading="lazy"
                                        onerror="this.style.display='none';this.closest('.game-card__cover').dataset.emoji='<?= $game['emoji'] ?>';this.closest('.game-card__cover').textContent='<?= $game['emoji'] ?>';">
                                    <div class="game-card__rating-badge">
                                        <svg viewBox="0 0 24 24">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                        </svg>
                                        <?= number_format($game['rating'], 1) ?>
                                    </div>
                                </div>
                                <div class="game-card__body">
                                    <h3 class="game-card__title"><?= htmlspecialchars($game['title']) ?></h3>
                                    <p class="game-card__genre"><?= htmlspecialchars($game['genre']) ?></p>
                                    <div class="game-card__meta">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                        <?= $game['playTime'] ?>h joué
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>

            </section>

            <!-- ═══════════════════════════════════════════
         PROCHAINES SESSIONS
    ════════════════════════════════════════════════ -->
            <div class="sessions-bg">
                <section class="section" aria-labelledby="sessions-title">

                    <div class="section__header">
                        <div class="section__titles">
                            <h2 id="sessions-title">Prochaines Sessions</h2>
                            <p>Rejoignez vos amis en jeu</p>
                        </div>
                        <a href="/sessions.php" class="section__see-all">
                            Voir tout
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </a>
                    </div>

                    <!-- TODO Jour 25 : remplacer par fetch('/api/sessions?status=upcoming') en AJAX -->
                    <div class="sessions-list" role="list">
                        <?php
                        $sessions = [
                            ['id' => '1', 'game' => 'Valorant', 'host' => 'ProGamer123', 'date' => '2026-03-21', 'time' => '20:00', 'players' => 4, 'max' => 5],
                            ['id' => '3', 'game' => 'Elden Ring', 'host' => 'SoulsBorne', 'date' => '2026-03-22', 'time' => '19:00', 'players' => 2, 'max' => 3],
                            ['id' => '4', 'game' => 'Cyberpunk 2077', 'host' => 'NightCityRunner', 'date' => '2026-03-25', 'time' => '21:00', 'players' => 1, 'max' => 2],
                        ];
                        foreach ($sessions as $s):
                            $dateObj = new DateTime($s['date']);
                            $dateStr = $dateObj->format('d/m/Y');
                            ?>
                            <article class="session-card" role="listitem">
                                <div class="session-card__info">
                                    <h3 class="session-card__title"><?= htmlspecialchars($s['game']) ?></h3>
                                    <p class="session-card__host">Organisé par <?= htmlspecialchars($s['host']) ?></p>
                                    <div class="session-card__meta">
                                        <span>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                            <?= htmlspecialchars($dateStr) ?> à <?= htmlspecialchars($s['time']) ?>
                                        </span>
                                        <span>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                            <?= $s['players'] ?>/<?= $s['max'] ?> joueurs
                                        </span>
                                    </div>
                                </div>
                                <div class="session-card__action">
                                    <a href="/sessions.php?id=<?= htmlspecialchars($s['id']) ?>"
                                        class="btn btn--primary btn--sm">
                                        Rejoindre
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                </section>
            </div>

        </main>

        <!-- ═══════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════ -->
        <footer class="footer" role="contentinfo">
            <p>© <?= date('Y') ?> GameVault — Projet DWWM · PHP 8.2 · MySQL 8 · JavaScript Vanilla</p>
        </footer>

    </div><!-- /.main-content -->

    <script src="/js/main.js"></script>
</body>

</html>