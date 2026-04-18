<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Ajouter un nouveau jeu</h1>
            <p>Recherchez un jeu via RAWG.io ou remplissez manuellement les informations.</p>
        </div>
    </header>

    <div class="card" style="max-width: 600px; margin: 2rem auto; padding: 2rem;">

        <!-- RAWG Search -->
        <div class="form-group rawg-search-wrapper" style="margin-bottom: 2rem; position: relative;">
            <label for="rawg-search" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">
                Rechercher un jeu (RAWG.io)
            </label>
            <input type="text" id="rawg-search" class="form-input" placeholder="Ex: Elden Ring, Zelda, Hades..."
                autocomplete="off" style="width:100%">
            <div id="rawg-results" class="rawg-results-dropdown" style="display:none;"></div>
        </div>

        <hr style="border: none; border-top: 1px solid var(--border); margin-bottom: 1.5rem;">

        <form action="/game/add" method="POST" enctype="multipart/form-data">
            <?php \App\Helpers\CsrfHelper::insertField(); ?>
            <input type="hidden" name="cover_image_url" id="cover_image_url" value="">
            <input type="hidden" name="platforms_rawg" id="platforms_rawg" value="">
            <input type="hidden" name="tags_rawg" id="tags_rawg" value="">

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

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="cover_image" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Image de
                    couverture</label>
                <img id="cover-preview" src="" alt="Aperçu" style="display:none; max-width:200px; border-radius:8px; margin-bottom:0.5rem;">
                <input type="file" name="cover_image" id="cover_image" class="form-input" accept="image/*"
                    style="width:100%">
                <small style="color: var(--text-muted);">Un fichier uploadé remplace l'image RAWG.</small>
            </div>

            <div class="form-actions" style="display:flex; justify-content: flex-end; gap: 1rem;">
                <a href="/games" class="btn btn--secondary">Annuler</a>
                <button type="submit" class="btn btn--primary">Ajouter au catalogue</button>
            </div>
        </form>
    </div>
</main>

<style>
.rawg-results-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--card-bg, #1a1a2e);
    border: 1px solid var(--border, #333);
    border-radius: 8px;
    max-height: 350px;
    overflow-y: auto;
    z-index: 100;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
}
.rawg-result-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: background 0.15s;
}
.rawg-result-item:hover {
    background: var(--hover-bg, rgba(255,255,255,0.05));
}
.rawg-result-item + .rawg-result-item {
    border-top: 1px solid var(--border, #333);
}
.rawg-result-img {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    flex-shrink: 0;
}
.rawg-result-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    min-width: 0;
}
.rawg-result-info strong {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rawg-result-meta {
    font-size: 0.8rem;
    color: var(--text-muted, #888);
}
.rawg-result-tags {
    font-size: 0.75rem;
    color: var(--accent, #6c63ff);
}
.rawg-no-result {
    cursor: default;
    color: var(--text-muted, #888);
    justify-content: center;
}
</style>

<script src="/js/rawg-search.js"></script>
