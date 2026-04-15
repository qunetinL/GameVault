<?php
/**
 * GameVault — Login View
 */
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — GameVault</title>
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
                <h1 class="auth-title">Ravi de vous revoir</h1>
                <p class="auth-subtitle">Entrez vos identifiants pour accéder à votre voûte.</p>
            </header>

            <?php if (isset($error)): ?>
                <div class="alert alert--error" style="margin-bottom: 1rem; color: #ff5555;">
                    <?= htmlspecialchars($error) ?>
                    <?php if (!empty($showResendLink) && !empty($resendEmail)): ?>
                        <br><a href="/resend-verification?email=<?= urlencode($resendEmail) ?>"
                               style="color: #bd93f9; text-decoration: underline; font-size: 0.85rem;">
                            Renvoyer l'email de vérification
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form action="/login" method="POST" class="auth-form">
                <?php \App\Helpers\CsrfHelper::insertField(); ?>

                <div class="form-group">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="nom@exemple.com"
                        required>
                </div>

                <div class="form-group">
                    <div style="display:flex; justify-content:space-between; align-items:center">
                        <label for="password" class="form-label">Mot de passe</label>
                        <a href="/forgot-password" class="forgot-link">Oublié ?</a>
                    </div>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••"
                        required>
                </div>

                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:0.5rem">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" class="form-label" style="font-size: 0.875rem;">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn btn--primary w-full" style="margin-top:1rem">
                    Se connecter
                </button>

            </form>

            <footer class="form-footer">
                Pas encore de compte ? <a href="/register">Créer un profil</a>
            </footer>

        </div>
    </main>

</body>

</html>