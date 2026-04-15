<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Modifier le jeu</h1>
            <p>Mettez à jour les informations de "
                <?= htmlspecialchars($game['title']) ?>".
            </p>
        </div>
    </header>

    <div class="card" style="max-width: 600px; margin: 2rem auto; padding: 2rem;">
        <form action="/game/edit?id=<?= $game['id'] ?>" method="POST" enctype="multipart/form-data">
            <?php \App\Helpers\CsrfHelper::insertField(); ?>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="title" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Titre du jeu</label>
                <input type="text" name="title" id="title" class="form-input"
                    value="<?= htmlspecialchars($game['title']) ?>" required style="width:100%">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="description"
                    style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Description</label>
                <textarea name="description" id="description" class="form-input" rows="4"
                    style="width:100%"><?= htmlspecialchars($game['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row" style="display:flex; gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="flex:1">
                    <label for="release_date" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Date de
                        sortie</label>
                    <input type="date" name="release_date" id="release_date" class="form-input"
                        value="<?= htmlspecialchars($game['release_date'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%">
                </div>
                <div class="form-group" style="flex:1">
                    <label for="rating" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Note /10</label>
                    <input type="number" name="rating" id="rating" step="0.1" min="0" max="10" class="form-input"
                        value="<?= htmlspecialchars($game['rating'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="cover_image" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Changer l'image
                    de couverture</label>
                <input type="file" name="cover_image" id="cover_image" class="form-input" accept="image/*"
                    style="width:100%">
                <?php if ($game['cover_image']): ?>
                    <p style="margin-top: 0.5rem; font-size: 0.8rem; opacity: 0.6;">Image actuelle :
                        <?= basename($game['cover_image']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="form-actions" style="display:flex; justify-content: flex-end; gap: 1rem;">
                <a href="/games/<?= $game['id'] ?>" class="btn btn--secondary">Annuler</a>
                <button type="submit" class="btn btn--primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</main>