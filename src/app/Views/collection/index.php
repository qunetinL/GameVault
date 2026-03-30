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
            <input type="text" id="search-games" class="form-input" placeholder="Rechercher un jeu...">
        </div>

        <select id="filter-genre" class="filter-select">
            <?php foreach ($genres as $g): ?>
                <option>
                    <?= htmlspecialchars($g) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select class="filter-select">
            <?php foreach ($platforms as $p): ?>
                <option>
                    <?= htmlspecialchars($p) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select id="sort-games" class="filter-select">
            <option value="default">Trier par...</option>
            <option value="name">Nom (A-Z)</option>
            <option value="rating">Note (Haute)</option>
        </select>
    </div>

    <!-- Collection Grid -->
    <div class="games-grid" id="games-grid">
        <?php foreach ($games as $game): ?>
            <article class="game-card">
                <a href="/game?id=<?= $game['id'] ?>" style="display:contents">
                    <div class="game-card__cover">
                        <img src="<?= $game['cover_image'] ?>" alt="<?= $game['title'] ?>" loading="lazy"
                            onerror="this.style.display='none';this.closest('.game-card__cover').textContent='🎮';">
                        <div class="game-card__rating-badge">
                            <svg viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <?= $game['personal_rating'] ?? $game['rating'] ?>
                        </div>
                    </div>
                    <div class="game-card__body">
                        <h3 class="game-card__title">
                            <?= htmlspecialchars($game['title']) ?>
                        </h3>
                        <p class="game-card__genre">
                            <?= htmlspecialchars($game['genre']) ?> •
                            <?= htmlspecialchars($game['platform']) ?>
                        </p>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</main>