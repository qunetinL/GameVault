<?php
/**
 * GameVault — Email Verification View
 */
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification — GameVault</title>
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
                <h1 class="auth-title">Vérification Email</h1>
            </header>

            <?php if (isset($success)): ?>
                <div class="alert alert--success" style="margin-bottom: 1rem; color: #50fa7b; text-align:center;">
                    <?= htmlspecialchars($success) ?>
                </div>
                <a href="/login" class="btn btn--primary w-full" style="display:block; text-align:center; margin-top:1rem;">
                    Se connecter
                </a>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert--error" style="margin-bottom: 1rem; color: #ff5555; text-align:center;">
                    <?= htmlspecialchars($error) ?>
                </div>
                <a href="/login" class="btn btn--primary w-full" style="display:block; text-align:center; margin-top:1rem;">
                    Retour à la connexion
                </a>
            <?php endif; ?>

            <?php if (isset($info)): ?>
                <div class="alert" style="margin-bottom: 1rem; color: #8be9fd; text-align:center;">
                    <?= htmlspecialchars($info) ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

</body>

</html>
