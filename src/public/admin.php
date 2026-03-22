<?php
// Simulation de données Admin
$users = [
    ['id' => 1, 'name' => 'ProGamer123', 'email' => 'pro@gamevault.com', 'role' => 'Admin', 'status' => 'Actif'],
    ['id' => 2, 'name' => 'SoulsMaster', 'email' => 'souls@gmail.com', 'role' => 'Modérateur', 'status' => 'Actif'],
    ['id' => 3, 'name' => 'SoloPlayer', 'email' => 'solo@yahoo.fr', 'role' => 'Membre', 'status' => 'Banni'],
    ['id' => 4, 'name' => 'NightWanderer', 'email' => 'night@outlook.com', 'role' => 'Membre', 'status' => 'Actif'],
];

// Navigation
$navItems = [
    ['href' => '/', 'label' => 'Accueil', 'icon' => 'home'],
    ['href' => '/dashboard.php', 'label' => 'Dashboard', 'icon' => 'dashboard'],
    ['href' => '/collection.php', 'label' => 'Collection', 'icon' => 'gamepad'],
    ['href' => '/sessions.php', 'label' => 'Sessions', 'icon' => 'calendar'],
    ['href' => '/chat.php', 'label' => 'Chat', 'icon' => 'message'],
    ['href' => '/admin.php', 'label' => 'Admin', 'icon' => 'shield'],
];

$icons = [
    'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
    'dashboard' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
    'gamepad' => '<rect x="2" y="6" width="20" height="12" rx="2"/><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/>',
    'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
    'message' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
    'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
];
$currentPath = "/admin.php";
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — GameVault</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

    <aside class="sidebar" role="navigation" aria-label="Navigation principale">
        <a href="/" class="sidebar__logo" aria-label="GameVault — Accueil">
            <div class="sidebar__logo-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="6" width="20" height="12" rx="2" />
                    <path d="M12 12h.01M8 12h.01M16 12h.01" />
                    <path d="M6 9v6M18 9v6" />
                </svg>
            </div>
            <span class="sidebar__logo-text">GameVault</span>
        </a>
        <nav class="sidebar__nav">
            <?php foreach ($navItems as $item):
                $active = ($item['href'] === $currentPath);
                ?>
                <a href="<?= htmlspecialchars($item['href']) ?>" class="sidebar__link<?= $active ? ' active' : '' ?>">
                    <span class="sidebar__link-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <?= $icons[$item['icon']] ?>
                        </svg>
                    </span>
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <header class="mobile-header">
        <a href="/" class="mobile-header__logo">
            <div class="mobile-header__logo-icon">🎮</div>
            <span class="mobile-header__logo-text">GameVault</span>
        </a>
        <button class="mobile-header__btn" id="hamburger-btn">☰</button>
    </header>

    <div class="main-content">
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
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card__icon" style="background: rgba(139, 92, 246, 0.1); color: var(--primary);">👤
                    </div>
                    <div>
                        <div class="stat-card__value">1,248</div>
                        <div class="stat-card__label">Utilisateurs totaux</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">📅</div>
                    <div>
                        <div class="stat-card__value">142</div>
                        <div class="stat-card__label">Sessions ce mois</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">⚠️</div>
                    <div>
                        <div class="stat-card__value">5</div>
                        <div class="stat-card__label">Signalements en attente</div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="avatar--sm"></div>
                                        <span>
                                            <?= htmlspecialchars($user['name']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>
                                <td><span class="badge">
                                        <?= htmlspecialchars($user['role']) ?>
                                    </span></td>
                                <td>
                                    <span
                                        class="badge <?= $user['status'] == 'Banni' ? 'badge--danger' : 'badge--success' ?>">
                                        <?= htmlspecialchars($user['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn-icon-sm" title="Modifier"><svg width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg></button>
                                        <button class="btn-icon-sm" title="Supprimer"><svg width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="footer">
            <p>©
                <?= date('Y') ?> GameVault — Projet DWWM
            </p>
        </footer>
    </div>

    <script src="/js/main.js"></script>
</body>

</html>