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
                <div style="font-size: 1.5rem; font-weight: bold;">1,248</div>
                <div style="opacity: 0.7; font-size: 0.9rem;">Utilisateurs totaux</div>
            </div>
        </div>
        <div class="stat-card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📅</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: bold;">142</div>
                <div style="opacity: 0.7; font-size: 0.9rem;">Sessions ce mois</div>
            </div>
        </div>
        <div class="stat-card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚠️</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: bold;">5</div>
                <div style="opacity: 0.7; font-size: 0.9rem;">Signalements en attente</div>
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
                            <?= htmlspecialchars($user['name']) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?= htmlspecialchars($user['email']) ?>
                        </td>
                        <td style="padding: 1rem;"><span class="badge"
                                style="background: var(--primary); padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">
                                <?= htmlspecialchars($user['role']) ?>
                            </span></td>
                        <td style="padding: 1rem;">
                            <span
                                style="padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; background: <?= $user['status'] == 'Banni' ? '#EF4444' : '#10B981' ?>;">
                                <?= htmlspecialchars($user['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <button class="btn btn--icon-only btn--outline"
                                style="padding: 4px; border-radius: 4px;">⚙️</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>