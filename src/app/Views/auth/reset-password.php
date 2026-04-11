<?php
/**
 * GameVault — Reset Password View
 */
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation — GameVault</title>
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
                <h1 class="auth-title">Nouveau mot de passe</h1>
                <p class="auth-subtitle">Choisissez un nouveau mot de passe sécurisé.</p>
            </header>

            <?php if (isset($error)): ?>
                <div class="alert alert--error" style="margin-bottom: 1rem; color: #ff5555;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($token)): ?>
            <form action="/reset-password" method="POST" class="auth-form">
                <?php \App\Helpers\CsrfHelper::insertField(); ?>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="form-group">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input"
                        placeholder="Minimum 8 caractères" required minlength="8">
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input"
                        placeholder="Retapez votre mot de passe" required minlength="8">
                </div>

                <button type="submit" class="btn btn--primary w-full" style="margin-top:1rem">
                    Réinitialiser
                </button>
            </form>
            <?php endif; ?>

            <footer class="form-footer">
                <a href="/login">Retour à la connexion</a>
            </footer>

        </div>
    </main>

</body>

</html>
