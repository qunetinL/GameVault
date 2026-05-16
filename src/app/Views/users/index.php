<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Membres</h1>
            <p>Découvrez les joueurs de la communauté GameVault.</p>
        </div>
    </header>

    <div class="games-grid" style="margin-top: 2rem;">
        <?php foreach ($users as $user): ?>
            <a href="/user?id=<?= $user['id'] ?>" style="text-decoration: none; color: inherit;">
                <div class="card" style="padding: 1.5rem; border-radius: 1rem; background: var(--card); border: 1px solid var(--border); transition: transform 0.2s, border-color 0.2s; cursor: pointer;"
                     onmouseover="this.style.borderColor='var(--accent, #6c5ce7)';this.style.transform='translateY(-2px)'"
                     onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--accent, #6c5ce7); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 700; color: white;">
                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 1.1rem;"><?= htmlspecialchars($user['username']) ?></h3>
                            <span style="font-size: 0.8rem; opacity: 0.6;"><?= $user['game_count'] ?> jeu<?= $user['game_count'] > 1 ? 'x' : '' ?> en collection</span>
                        </div>
                    </div>
                    <?php if (!empty($user['stores'])): ?>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.3rem;">
                            <?php foreach ($user['stores'] as $store): ?>
                                <span style="font-size: 0.7rem; padding: 2px 8px; border-radius: 4px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1);">
                                    <?= htmlspecialchars($store['name']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</main>
