<?php
/**
 * GameVault — Register Page
 */
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — GameVault</title>
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
                <h1 class="auth-title">Créer un compte</h1>
                <p class="auth-subtitle">Rejoignez la communauté et gérez votre collection.</p>
            </header>

            <form action="/login.php" method="POST" class="auth-form">

                <div class="form-group">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" class="form-input" placeholder="ProGamer123"
                        required minlength="3">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="nom@exemple.com"
                        required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input"
                        placeholder="Minimum 8 caractères" required minlength="8">
                </div>

                <div class="form-group" style="display:flex; align-items:flex-start; gap:0.5rem; margin-top:1.5rem">
                    <input type="checkbox" id="terms" required style="margin-top: 0.25rem;">
                    <label for="terms" class="form-label" style="font-size: 0.75rem; color: var(--muted-fg)">
                        J'accepte les <a href="#" style="color:var(--primary)">Conditions d'utilisation</a> et la <a
                            href="#" style="color:var(--primary)">Politique de confidentialité</a>.
                    </label>
                </div>

                <button type="submit" class="btn btn--primary w-full" style="margin-top:1rem">
                    S'inscrire
                </button>

            </form>

            <footer class="form-footer">
                Déjà inscrit ? <a href="/login.php">Se connecter</a>
            </footer>

        </div>
    </main>

</body>

</html>