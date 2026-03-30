<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>
                <?= htmlspecialchars($game['title']) ?>
            </h1>
            <p>
                <?= htmlspecialchars($game['genre']) ?> •
                <?= htmlspecialchars($game['platform']) ?>
            </p>
        </div>
    </header>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-top: 2rem;">
        <div class="card" style="overflow: hidden; border-radius: 1rem; border: 1px solid var(--border);">
            <img src="<?= $game['cover_image'] ?>" alt="<?= htmlspecialchars($game['title']) ?>"
                style="width: 100%; height: auto; display: block;">
        </div>
        <div>
            <div class="card"
                style="padding: 2rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
                <h2>Détails du jeu</h2>
                <p style="margin-top: 1rem; line-height: 1.6; opacity: 0.8;">
                    <?= htmlspecialchars($game['description']) ?>
                </p>
                <div style="margin-top: 2rem;">
                    <strong>Note :</strong>
                    <?= $game['rating'] ?> / 5 ⭐
                </div>
                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button class="btn btn--primary">Lancer une session</button>
                    <button class="btn btn--outline">Modifier les détails</button>
                </div>
            </div>
        </div>
    </div>
</main>