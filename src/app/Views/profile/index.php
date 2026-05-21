<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Mon Profil</h1>
            <p>Gérez vos informations personnelles et vos données.</p>
        </div>
    </header>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert--success" style="margin-top: 1rem; padding: 1rem; background: rgba(80, 200, 120, 0.15); border: 1px solid rgba(80, 200, 120, 0.3); border-radius: 0.5rem; color: #50c878;">
            Profil mis à jour avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert--error" style="margin-top: 1rem; padding: 1rem; background: rgba(255, 85, 85, 0.15); border: 1px solid rgba(255, 85, 85, 0.3); border-radius: 0.5rem; color: #ff5555;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; margin-top: 2rem;">

        <!-- Informations du compte -->
        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border);">
            <h2 style="margin-bottom: 1.5rem;">Informations du compte</h2>
            <div style="margin-bottom: 1rem;">
                <strong>Nom d'utilisateur :</strong>
                <span><?= htmlspecialchars($user['username']) ?></span>
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Email :</strong>
                <span><?= htmlspecialchars($user['email']) ?></span>
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Date d'inscription :</strong>
                <span><?= htmlspecialchars(date('d/m/Y à H:i', strtotime($user['created_at']))) ?></span>
            </div>
        </div>

        <!-- Modifier le profil -->
        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border);">
            <h2 style="margin-bottom: 1.5rem;">Modifier le profil</h2>
            <form action="/profile/update" method="POST">
                <?php \App\Helpers\CsrfHelper::insertField(); ?>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" class="form-input"
                           value="<?= htmlspecialchars($user['username']) ?>" required minlength="3">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-input"
                           value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="current_password_update" class="form-label">Mot de passe actuel (confirmation requise)</label>
                    <input type="password" id="current_password_update" name="current_password" class="form-input"
                           placeholder="Entrez votre mot de passe actuel" required>
                </div>

                <button type="submit" class="btn btn--primary" style="width: 100%;">Enregistrer les modifications</button>
            </form>
        </div>

    </div>

    <!-- Demandes d'amis + Liste d'amis -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">

        <?php if (!empty($pendingRequests)): ?>
        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid rgba(108, 92, 231, 0.3);">
            <h2 style="margin-bottom: 1rem;">Demandes d'amis (<?= count($pendingRequests) ?>)</h2>
            <?php foreach ($pendingRequests as $req): ?>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
                    <a href="/user?id=<?= $req['user_id'] ?>" style="color: inherit; text-decoration: none; font-weight: 600;">
                        <?= htmlspecialchars($req['username']) ?>
                    </a>
                    <form action="/friend/respond" method="POST" style="display: flex; gap: 0.5rem;">
                        <?php \App\Helpers\CsrfHelper::insertField(); ?>
                        <input type="hidden" name="friendship_id" value="<?= $req['friendship_id'] ?>">
                        <button type="submit" name="status" value="accepted" class="btn btn--primary" style="font-size: 0.85rem; padding: 0.3rem 0.8rem;">Accepter</button>
                        <button type="submit" name="status" value="rejected" class="btn btn--secondary" style="font-size: 0.85rem; padding: 0.3rem 0.8rem;">Refuser</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border);">
            <h2 style="margin-bottom: 1rem;">Mes amis (<?= count($friends) ?>)</h2>
            <?php if (empty($friends)): ?>
                <p style="opacity: 0.6;">Vous n'avez pas encore d'amis. Visitez la page <a href="/users" style="color: var(--primary);">Membres</a> pour en trouver !</p>
            <?php else: ?>
                <?php foreach ($friends as $friend): ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
                        <a href="/user?id=<?= $friend['id'] ?>" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--accent, #6c5ce7); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700; color: white;">
                                <?= strtoupper(substr($friend['username'], 0, 1)) ?>
                            </div>
                            <span style="font-weight: 600;"><?= htmlspecialchars($friend['username']) ?></span>
                        </a>
                        <form action="/friend/remove" method="POST">
                            <?php \App\Helpers\CsrfHelper::insertField(); ?>
                            <input type="hidden" name="friend_id" value="<?= $friend['id'] ?>">
                            <button type="submit" class="btn btn--secondary" style="font-size: 0.8rem; padding: 0.3rem 0.8rem;" onclick="return confirm('Supprimer cet ami ?')">Retirer</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- Mes bibliotheques -->
    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 1.5rem;">
        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border);">
            <h2 style="margin-bottom: 1rem;">Mes bibliothèques</h2>
            <p style="margin-bottom: 1rem; opacity: 0.8;">Cochez les plateformes sur lesquelles vous avez un compte.</p>
            <form action="/profile/stores" method="POST">
                <?php \App\Helpers\CsrfHelper::insertField(); ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem;">
                    <?php foreach ($allStores as $store): ?>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border); transition: background 0.2s;">
                            <input type="checkbox" name="stores[]" value="<?= $store['id'] ?>"
                                <?= in_array($store['id'], $userStoreIds) ? 'checked' : '' ?>>
                            <span><?= htmlspecialchars($store['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn btn--primary">Enregistrer mes bibliothèques</button>
            </form>
        </div>
    </div>

    <!-- Actions RGPD -->
    <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">

        <!-- Export des données -->
        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border);">
            <h2 style="margin-bottom: 1rem;">Portabilité des données</h2>
            <p style="margin-bottom: 1rem; opacity: 0.8;">Conformément à l'Art. 20 du RGPD, vous pouvez exporter l'ensemble de vos données personnelles au format JSON.</p>
            <a href="/profile/export" class="btn btn--primary" style="display: inline-block; text-decoration: none;">Exporter mes données</a>
        </div>

        <!-- Suppression du compte -->
        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); border-color: rgba(255, 85, 85, 0.3);">
            <h2 style="margin-bottom: 1rem; color: #ff5555;">Supprimer mon compte</h2>
            <p style="margin-bottom: 1rem; opacity: 0.8;">Conformément à l'Art. 17 du RGPD (droit à l'effacement), vous pouvez supprimer définitivement votre compte et toutes les données associées. Cette action est irréversible.</p>
            <button type="button" class="btn" style="background: #ff5555; color: white; border: none; cursor: pointer;"
                    onclick="document.getElementById('delete-modal').style.display='flex'">
                Supprimer mon compte
            </button>
        </div>

    </div>
</main>

<!-- Modal de confirmation de suppression -->
<div id="delete-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); max-width: 450px; width: 90%;">
        <h2 style="margin-bottom: 1rem; color: #ff5555;">Confirmer la suppression</h2>
        <p style="margin-bottom: 1.5rem;">Cette action est irréversible. Toutes vos données seront définitivement supprimées (collection, messages, sessions, votes).</p>

        <form action="/profile/delete" method="POST">
            <?php \App\Helpers\CsrfHelper::insertField(); ?>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="current_password_delete" class="form-label">Confirmez votre mot de passe</label>
                <input type="password" id="current_password_delete" name="current_password" class="form-input"
                       placeholder="Votre mot de passe" required>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="button" class="btn" style="flex: 1; background: var(--muted); color: var(--fg); border: none; cursor: pointer;"
                        onclick="document.getElementById('delete-modal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn" style="flex: 1; background: #ff5555; color: white; border: none; cursor: pointer;">
                    Supprimer définitivement
                </button>
            </div>
        </form>
    </div>
</div>
