<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Sessions Gaming</h1>
            <p>Rejoignez d'autres joueurs pour des sessions inoubliables.</p>
        </div>
        <div class="header-actions">
            <a href="/session/create" class="btn btn--primary">Créer une session</a>
        </div>
    </header>

    <div class="sessions-grid"
        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <?php foreach ($sessions as $session): ?>
            <div class="card session-card"
                style="background: var(--card); border: 1px solid var(--border); border-radius: 1rem; overflow: hidden; transition: transform 0.2s ease; display: flex; flex-direction: column;">
                <div style="padding: 1.5rem; flex-grow: 1;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <span
                            class="badge badge--<?= $session['status'] === 'planned' ? 'primary' : ($session['status'] === 'in_progress' ? 'success' : 'secondary') ?>"
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            <?= htmlspecialchars($session['status']) ?>
                        </span>
                        <div style="font-size: 0.85rem; opacity: 0.6;">
                            👥 <?= htmlspecialchars($session['max_players']) ?> max
                        </div>
                    </div>

                    <h3 style="margin-bottom: 0.75rem; font-size: 1.25rem; font-weight: 600; color: var(--foreground);">
                        <?= htmlspecialchars($session['title']) ?>
                    </h3>

                    <p style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 1.5rem; line-height: 1.5;">
                        <?= htmlspecialchars(mb_strimwidth($session['description'], 0, 100, "...")) ?>
                    </p>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem; font-size: 0.9rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="opacity: 0.5;">📅</span>
                            <span><?= date('d M Y, H:i', strtotime($session['scheduled_at'])) ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="opacity: 0.5;">👤 Organisé par</span>
                            <span style="font-weight: 500;"><?= htmlspecialchars($session['organizer_name']) ?></span>
                        </div>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; background: rgba(255,255,255,0.02); border-top: 1px solid var(--border);">
                    <a href="/session/show?id=<?= $session['id'] ?>" class="btn btn--outline" style="width: 100%;">Voir les
                        détails</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>