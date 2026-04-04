<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <a href="/sessions"
                style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--primary); font-size: 0.9rem; margin-bottom: 1rem;">
                ← Retour aux sessions
            </a>
            <h1>
                <?= htmlspecialchars($session['title']) ?>
            </h1>
            <p>
                <?= htmlspecialchars($session['organizer_name']) ?> —
                <?= date('d M Y à H:i', strtotime($session['scheduled_at'])) ?>
            </p>
        </div>
        <div class="header-actions">
            <span
                class="badge badge--<?= $session['status'] === 'planned' ? 'primary' : ($session['status'] === 'in_progress' ? 'success' : 'secondary') ?>">
                <?= htmlspecialchars($session['status']) ?>
            </span>
        </div>
    </header>

    <div class="layout-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
        <!-- Left Column: Details & Voting -->
        <div class="content-main">
            <section class="card" style="padding: 2rem; margin-bottom: 2rem;">
                <h2 style="margin-bottom: 1rem; font-size: 1.5rem;">Description</h2>
                <p style="line-height: 1.6; opacity: 0.8;">
                    <?= nl2br(htmlspecialchars($session['description'])) ?>
                </p>
            </section>

            <section class="card" style="padding: 2rem;">
                <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;">Vote pour le jeu</h2>

                <?php if ($session['selected_game_id']): ?>
                    <div
                        style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; padding: 1rem; border-radius: 0.5rem; color: #10b981; display: flex; align-items: center; gap: 1rem;">
                        <span style="font-size: 1.5rem;">🎯</span>
                        <div>
                            <strong>Jeu sélectionné :</strong>
                            <?= htmlspecialchars($session['selected_game_title']) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="votes-list" style="display: grid; gap: 1rem; margin-bottom: 2rem;">
                        <?php foreach ($votes as $vote): ?>
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 0.5rem;">
                                <span>
                                    <?= htmlspecialchars($vote['title']) ?>
                                </span>
                                <span class="badge badge--secondary">
                                    <?= $vote['vote_count'] ?> vote
                                    <?= $vote['vote_count'] > 1 ? 's' : '' ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($votes)): ?>
                            <p style="opacity: 0.5; font-style: italic;">Aucun vote pour le moment.</p>
                        <?php endif; ?>
                    </div>

                    <form action="/session/vote" method="POST"
                        style="background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 0.5rem; border: 1px dashed var(--border);">
                        <input type="hidden" name="session_id" value="<?= $session['id'] ?>">
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 500;">Voter pour un jeu :</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <select name="game_id" class="form-input" style="flex: 1;">
                                <?php foreach ($games as $game): ?>
                                    <option value="<?= $game['id'] ?>">
                                        <?= htmlspecialchars($game['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn--primary">Voter</button>
                        </div>
                    </form>
                <?php endif; ?>
            </section>
        </div>

        <!-- Right Column: Participants & Invitations -->
        <div class="content-sidebar">
            <section class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
                <h2 style="margin-bottom: 1.25rem; font-size: 1.25rem;">Participants (
                    <?= count($participants) ?>/
                    <?= $session['max_players'] ?>)
                </h2>
                <div style="display: grid; gap: 0.75rem;">
                    <?php foreach ($participants as $p): ?>
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; border-radius: 0.5rem; background: rgba(255,255,255,0.02);">
                            <span style="font-weight: 500; font-size: 0.95rem;">
                                <?= htmlspecialchars($p['username']) ?>
                                <?php if ($p['id'] === $session['organizer_id']): ?>
                                    <small style="color: var(--primary); margin-left: 0.25rem;">(Org)</small>
                                <?php endif; ?>
                            </span>
                            <span
                                class="badge badge--<?= $p['status'] === 'accepted' ? 'success' : ($p['status'] === 'refused' ? 'danger' : 'secondary') ?>"
                                style="font-size: 0.7rem;">
                                <?= $p['status'] ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($session['organizer_id'] === $_SESSION['user_id']): ?>
                    <hr style="margin: 1.5rem 0; opacity: 0.1;">
                    <form action="/session/invite" method="POST">
                        <input type="hidden" name="session_id" value="<?= $session['id'] ?>">
                        <label style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 500;">Inviter
                            un joueur :</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="text" name="username" class="form-input" placeholder="Pseudo"
                                style="width: 100%; font-size: 0.9rem;">
                            <button type="submit" class="btn btn--sm btn--primary">Inviter</button>
                        </div>
                    </form>
                <?php endif; ?>
            </section>

            <?php
            $myStatus = 'none';
            foreach ($participants as $p)
                if ($p['id'] === $_SESSION['user_id'])
                    $myStatus = $p['status'];
            if ($myStatus === 'pending'): ?>
                <section class="card"
                    style="padding: 1.5rem; border: 2px solid var(--primary); background: rgba(139, 92, 246, 0.05);">
                    <h3 style="margin-bottom: 1rem; color: var(--primary);">Invitation reçue !</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <form action="/session/respond" method="POST">
                            <input type="hidden" name="session_id" value="<?= $session['id'] ?>">
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn btn--outline"
                                style="width:100%; color: #10b981;">Accepter</button>
                        </form>
                        <form action="/session/respond" method="POST">
                            <input type="hidden" name="session_id" value="<?= $session['id'] ?>">
                            <input type="hidden" name="status" value="refused">
                            <button type="submit" class="btn btn--outline"
                                style="width:100%; color: #ef4444;">Refuser</button>
                        </form>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>
</main>