<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>
                <?= htmlspecialchars($game['title']) ?>
            </h1>
            <p>
                <?= htmlspecialchars($game['tags'] ?? 'Sans étiquette') ?> •
                <?= htmlspecialchars($game['platforms'] ?? 'Multi') ?>
            </p>
        </div>
        <div class="header-actions">
            <form action="/game/toggle?id=<?= $game['id'] ?>" method="POST">
                <?php \App\Helpers\CsrfHelper::insertField(); ?>
                <button type="submit" class="btn <?= $inCollection ? 'btn--secondary' : 'btn--primary' ?>">
                    <?= $inCollection ? 'Retirer de ma collection' : 'Ajouter à ma collection' ?>
                </button>
            </form>
            <a href="/game/edit?id=<?= $game['id'] ?>" class="btn btn--secondary">Modifier</a>
        </div>
    </header>

    <div class="game-detail" style="display:flex; gap: 3rem; margin-top: 2rem;">
        <div class="game-detail__cover" style="flex: 0 0 300px;">
            <img src="<?= htmlspecialchars($game['cover_image'] ?? '/img/placeholder.png', ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars($game['title']) ?>"
                style="width:100%; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
            <div class="game-detail__rating" style="margin-top: 1.5rem; text-align: center;">
                <span style="font-size: 2.5rem; font-weight: 700; color: var(--accent-color, #f39c12);">
                    <?= number_format($game['rating'], 1) ?>
                </span>
                <span style="font-size: 1.2rem; opacity: 0.6;"> / 10</span>
            </div>
        </div>

        <div class="game-detail__content" style="flex:1">
            <h2 style="margin-bottom: 1rem;">Description</h2>
            <p style="font-size: 1.1rem; line-height: 1.6; opacity: 0.8; margin-bottom: 2rem;">
                <?= nl2br(htmlspecialchars($game['description'] ?? 'Aucune description disponible.')) ?>
            </p>

            <div class="game-info-grid"
                style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                <div class="info-item">
                    <h3
                        style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.5; margin-bottom: 0.5rem;">
                        Date de sortie</h3>
                    <p style="font-weight: 600;">
                        <?= $game['release_date'] ? date('d F Y', strtotime($game['release_date'])) : 'Inconnue' ?>
                    </p>
                </div>
                <!-- Add more info as needed -->
            </div>

            <?php if ($inCollection): ?>
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <h3 style="margin-bottom: 1rem;">Je possède ce jeu sur</h3>
                <form action="/game/stores?id=<?= $game['id'] ?>" method="POST">
                    <?php \App\Helpers\CsrfHelper::insertField(); ?>
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                        <?php foreach ($allStores as $store): ?>
                            <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; padding: 0.4rem 0.8rem; border-radius: 0.5rem; border: 1px solid var(--border); font-size: 0.9rem; transition: background 0.2s;">
                                <input type="checkbox" name="stores[]" value="<?= $store['id'] ?>"
                                    <?= in_array($store['id'], $gameStoreIds) ? 'checked' : '' ?>>
                                <span><?= htmlspecialchars($store['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn--primary" style="font-size: 0.9rem;">Enregistrer</button>
                </form>
            </div>
            <?php endif; ?>

            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <form action="/game/delete?id=<?= $game['id'] ?>" method="POST"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce jeu du catalogue ?');">
                    <?php \App\Helpers\CsrfHelper::insertField(); ?>
                    <button type="submit"
                        style="background:none; border:none; color:#e74c3c; cursor:pointer; text-decoration: underline;">Supprimer
                        ce jeu du catalogue</button>
                </form>
            </div>
        </div>
    </div>
</main>