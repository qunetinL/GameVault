<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Ajouter un nouveau jeu</h1>
            <p>Remplissez les informations pour ajouter un titre à la base de données.</p>
        </div>
    </header>

    <div class="card" style="max-width: 600px; margin: 2rem auto; padding: 2rem;">
        <form action="/game/add" method="POST" enctype="multipart/form-data">
            <?php \App\Helpers\CsrfHelper::insertField(); ?>
            <?php \App\Helpers\CsrfHelper::insertField(); ?>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="title" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Titre du jeu</label>
                <input type="text" name="title" id="title" class="form-input" required style="width:100%">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="description"
                    style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Description</label>
                <textarea name="description" id="description" class="form-input" rows="4" style="width:100%"></textarea>
            </div>

            <div class="form-row" style="display:flex; gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="flex:1">
                    <label for="release_date" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Date de
                        sortie</label>
                    <input type="date" name="release_date" id="release_date" class="form-input" style="width:100%">
                </div>
                <div class="form-group" style="flex:1">
                    <label for="rating" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Note initiale
                        /10</label>
                    <input type="number" name="rating" id="rating" step="0.1" min="0" max="10" class="form-input"
                        style="width:100%">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="cover_image" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Image de
                    couverture</label>
                <input type="file" name="cover_image" id="cover_image" class="form-input" accept="image/*"
                    style="width:100%">
            </div>

            <div class="form-actions" style="display:flex; justify-content: flex-end; gap: 1rem;">
                <a href="/games" class="btn btn--secondary">Annuler</a>
                <button type="submit" class="btn btn--primary">Ajouter au catalogue</button>
            </div>
        </form>
    </div>
</main>