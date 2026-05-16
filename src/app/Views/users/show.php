<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1><?= htmlspecialchars($profileUser['username']) ?></h1>
            <p>Membre depuis le <?= date('d/m/Y', strtotime($profileUser['created_at'])) ?></p>
        </div>
        <div class="header-actions">
            <a href="/users" class="btn btn--secondary">Retour aux membres</a>
        </div>
    </header>

    <?php if (!empty($userStores)): ?>
        <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
            <span style="font-weight: 600; opacity: 0.7;">Bibliothèques :</span>
            <?php foreach ($userStores as $store): ?>
                <span style="font-size: 0.85rem; padding: 4px 10px; border-radius: 6px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1);">
                    <?= htmlspecialchars($store['name']) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h2 style="margin-top: 2rem; margin-bottom: 1rem;">
        Collection (<?= count($games) ?> jeu<?= count($games) > 1 ? 'x' : '' ?>)
    </h2>

    <?php if (empty($games)): ?>
        <p style="opacity: 0.6;">Ce joueur n'a pas encore de jeux dans sa collection.</p>
    <?php else: ?>
        <div class="games-grid">
            <?php foreach ($games as $game): ?>
                <article class="game-card">
                    <a href="/game?id=<?= $game['id'] ?>" style="display:contents">
                        <div class="game-card__cover">
                            <img src="<?= $game['cover_image'] ?? '/img/placeholder.png' ?>"
                                 alt="<?= htmlspecialchars($game['title'], ENT_QUOTES, 'UTF-8') ?>"
                                 loading="lazy"
                                 onerror="this.style.display='none';this.closest('.game-card__cover').textContent='🎮';">
                            <?php if ($game['personal_rating']): ?>
                                <div class="game-card__rating-badge">
                                    <svg viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                    <?= $game['personal_rating'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="game-card__body">
                            <h3 class="game-card__title"><?= htmlspecialchars($game['title']) ?></h3>
                            <p class="game-card__genre">
                                <?= htmlspecialchars($game['tags'] ?? 'Sans étiquette') ?>
                            </p>
                            <p class="game-card__genre" style="font-size: 0.75rem; opacity: 0.7;">
                                <?= htmlspecialchars($game['user_stores'] ?? $game['platforms'] ?? '') ?>
                            </p>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
