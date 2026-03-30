<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Sessions Gaming</h1>
            <p>Rejoignez d'autres joueurs pour des sessions inoubliables.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn--primary">Créer une session</button>
        </div>
    </header>

    <div class="sessions-grid"
        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <?php foreach ($sessions as $session): ?>
            <div class="card"
                style="background: var(--card); border: 1px solid var(--border); border-radius: 1rem; overflow: hidden; transition: transform 0.2s ease;">
                <div
                    style="height: 150px; background-image: url('<?= $session['img'] ?>'); background-size: cover; background-position: center; position: relative;">
                    <div
                        style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.6); padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">
                        <?= htmlspecialchars($session['game']) ?>
                    </div>
                </div>
                <div style="padding: 1.25rem;">
                    <h3 style="margin-bottom: 0.5rem; font-size: 1.1rem;">
                        <?= htmlspecialchars($session['title']) ?>
                    </h3>
                    <div
                        style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.85rem; opacity: 0.7;">
                        <div>📅
                            <?= htmlspecialchars($session['date']) ?>
                        </div>
                        <div>👥
                            <?= htmlspecialchars($session['players']) ?>
                        </div>
                        <div>🏆
                            <?= htmlspecialchars($session['level']) ?>
                        </div>
                        <div>👤
                            <?= htmlspecialchars($session['host']) ?>
                        </div>
                    </div>
                    <button class="btn btn--outline" style="width: 100%; margin-top: 1rem;">Rejoindre la session</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>