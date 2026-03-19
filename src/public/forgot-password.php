<?php
/**
 * GameVault — Forgot Password Page
 */
?>
<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récupération — GameVault</title>
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
                <h1 class="auth-title">Mot de passe oublié ?</h1>
                <p class="auth-subtitle">Pas de panique. Entrez votre email pour recevoir un lien de réinitialisation.
                </p>
            </header>

            <form action="#" class="auth-form">

                <div class="form-group">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="nom@exemple.com"
                        required>
                </div>

                <button type="submit" class="btn btn--primary w-full" style="margin-top:1rem">
                    Envoyer le lien
                </button>

            </form>

            <footer class="form-footer">
                <a href="/login.php">Retour à la connexion</a>
            </footer>

        </div>
    </main>

</body>

</html>