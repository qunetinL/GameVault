<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Panneau d'Administration</h1>
            <p>Supervisez l'activité de la plateforme et gérez les utilisateurs.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn--outline">Exporter les données</button>
        </div>
    </header>

    <!-- Admin Stats -->
    <div class="stats-grid"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <div class="stat-card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">👤</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: bold;"><?= number_format($stats['total_users']) ?></div>
                <div style="opacity: 0.7; font-size: 0.9rem;">Utilisateurs totaux</div>
            </div>
        </div>
        <div class="stat-card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🎮</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: bold;"><?= number_format($stats['total_games']) ?></div>
                <div style="opacity: 0.7; font-size: 0.9rem;">Jeux au catalogue</div>
            </div>
        </div>
        <div class="stat-card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📅</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: bold;"><?= number_format($stats['total_sessions']) ?></div>
                <div style="opacity: 0.7; font-size: 0.9rem;">Sessions créées</div>
            </div>
        </div>
        <div class="stat-card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚠️</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: bold;"><?= number_format($stats['pending_games']) ?></div>
                <div style="opacity: 0.7; font-size: 0.9rem;">Jeux en attente</div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="admin-table-container"
        style="margin-top: 2rem; overflow-x: auto; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: rgba(255,255,255,0.05);">
                <tr>
                    <th style="padding: 1rem;">Utilisateur</th>
                    <th style="padding: 1rem;">Email</th>
                    <th style="padding: 1rem;">Rôle</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr style="border-top: 1px solid var(--border);">
                        <td style="padding: 1rem;">
                            <?= htmlspecialchars($user['username']) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?= htmlspecialchars($user['email']) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <span class="badge badge--<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span class="badge badge--<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= htmlspecialchars($user['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; display: flex; gap: 0.5rem;">
                            <form action="/admin/user/update" method="POST" style="margin: 0;">
                                <?php \App\Helpers\CsrfHelper::insertField(); ?>
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <?php if ($user['status'] === 'active'): ?>
                                    <button type="submit" name="action" value="ban" class="btn btn--sm btn--outline"
                                        style="color: var(--danger); border-color: var(--danger);"
                                        onclick="return confirm('Bannir cet utilisateur ?')">Bannir</button>
                                <?php else: ?>
                                    <button type="submit" name="action" value="unban" class="btn btn--sm btn--outline"
                                        style="color: var(--success); border-color: var(--success);">Gracier</button>
                                <?php endif; ?>

                                <?php if ($user['role'] !== 'admin'): ?>
                                    <button type="submit" name="action" value="promote" class="btn btn--sm btn--outline"
                                        onclick="return confirm('Promouvoir en Admin ?')">Promouvoir</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>