<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Exploration des Jeux</h1>
            <p>Découvrez de nouveaux titres et enrichissez votre collection.</p>
        </div>
        <div class="header-actions">
            <a href="/game/add" class="btn btn--primary">Ajouter un jeu</a>
        </div>
    </header>

    <div class="filter-bar">
        <form action="/games" method="GET" class="search-wrap" style="flex:1">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" name="q" class="form-input" placeholder="Rechercher un jeu..."
                value="<?= htmlspecialchars($query ?? '') ?>">
        </form>
    </div>

    <div class="games-grid">
        <?php foreach ($games as $game): ?>
            <article class="game-card">
                <a href="/game?id=<?= $game['id'] ?>" style="display:contents">
                    <div class="game-card__cover">
                        <img src="<?= $game['cover_image'] ?? '/img/placeholder.png' ?>"
                            alt="<?= htmlspecialchars($game['title']) ?>" loading="lazy"
                            onerror="this.src='/img/placeholder.png'">
                        <div class="game-card__rating-badge">
                            <svg viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <?= number_format($game['rating'], 1) ?>
                        </div>
                    </div>
                    <div class="game-card__body">
                        <h3 class="game-card__title">
                            <?= htmlspecialchars($game['title']) ?>
                        </h3>
                        <p class="game-card__genre">
                            <?= htmlspecialchars($game['tags'] ?? 'Sans étiquette') ?>
                        </p>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</main>