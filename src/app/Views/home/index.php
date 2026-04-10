<main class="section">
    <!-- Hero Section -->
    <section class="hero"
        style="padding: 4rem 2rem; background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(30, 41, 59, 0) 100%); border-radius: 1.5rem; margin-bottom: 3rem; position: relative; overflow: hidden; border: 1px solid var(--border);">
        <div style="max-width: 600px; position: relative; z-index: 2;">
            <h1 style="font-size: 3.5rem; line-height: 1.1; margin-bottom: 1.5rem; font-weight: 800;">Votre univers
                gaming, <span style="color: var(--primary);">centralisé.</span></h1>
            <p style="font-size: 1.1rem; opacity: 0.8; margin-bottom: 2rem;">Gérez votre collection, suivez vos sessions
                de jeu et connectez-vous avec vos amis en un clic.</p>
            <div style="display: flex; gap: 1rem;">
                <a href="/collection" class="btn btn--primary">Ma Collection</a>
                <a href="/sessions" class="btn btn--outline">Trouver une session</a>
            </div>
        </div>
        <div
            style="position: absolute; right: -50px; top: -50px; font-size: 15rem; opacity: 0.05; transform: rotate(15deg);">
            🎮</div>
    </section>

    <!-- Highlights Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Popular Games -->
        <section>
            <h2 style="margin-bottom: 1.5rem;">🔥 Jeux Populaires</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                <?php foreach ($popularGames as $game): ?>
                    <div class="card"
                        style="background: var(--card); border-radius: 1rem; overflow: hidden; border: 1px solid var(--border);">
                        <img src="<?= htmlspecialchars($game['cover_image'] ?? $game['img'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($game['title'], ENT_QUOTES, 'UTF-8') ?>"
                            style="width:100%; height: 120px; object-fit: cover;">
                        <div style="padding: 1rem;">
                            <h4 style="font-size: 0.9rem;">
                                <?= htmlspecialchars($game['title']) ?>
                            </h4>
                            <div style="font-size: 0.8rem; opacity: 0.7;">⭐
                                <?= htmlspecialchars($game['rating'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Upcoming Sessions -->
        <section>
            <h2 style="margin-bottom: 1.5rem;">📅 Prochaines Sessions</h2>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($nextSessions as $session): ?>
                    <div class="card"
                        style="padding: 1rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
                        <div
                            style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <span style="font-weight: bold; font-size: 0.9rem;">
                                <?= htmlspecialchars($session['title']) ?>
                            </span>
                            <span
                                style="font-size: 0.7rem; background: var(--primary); padding: 2px 6px; border-radius: 4px;">Live</span>
                        </div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">
                            <?= htmlspecialchars($session['date']) ?>
                        </div>
                        <div style="font-size: 0.8rem; opacity: 0.7; margin-top: 0.5rem;">Par
                            <?= htmlspecialchars($session['host']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <a href="/sessions"
                    style="text-align: center; font-size: 0.85rem; color: var(--primary); text-decoration: none;">Voir
                    toutes les sessions →</a>
            </div>
        </section>
    </div>
</main>