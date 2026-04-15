<?php
/**
 * GameVault — 429 Too Many Requests
 */
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Limite atteinte — GameVault') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

    <main class="auth-layout">
        <div class="auth-card">

            <header class="auth-header">
                <a href="/" class="auth-logo">
                    <div class="sidebar__logo-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <path d="M12 12h.01M8 12h.01M16 12h.01" />
                            <path d="M6 9v6M18 9v6" />
                        </svg>
                    </div>
                    <span class="sidebar__logo-text">GameVault</span>
                </a>
                <h1 class="auth-title">Accès temporairement limité</h1>
            </header>

            <div class="alert alert--error" style="margin-bottom: 1rem; color: #ff5555; text-align: center;">
                <?= htmlspecialchars($error ?? 'Trop de tentatives. Veuillez patienter une minute.') ?>
            </div>

            <p style="text-align: center; color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">
                Pour protéger votre compte, nous limitons le nombre de tentatives.<br>
                Veuillez réessayer dans quelques instants.
            </p>

            <a href="/login" class="btn btn--primary w-full" style="text-align: center; display: block;">
                Retour à la connexion
            </a>

        </div>
    </main>

</body>

</html>
