<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Créer une Session</h1>
            <p>Planifiez une rencontre et invitez vos amis.</p>
        </div>
    </header>

    <div class="card" style="max-width: 600px; margin: 2rem auto; padding: 2rem;">
        <form action="/session/create" method="POST">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="title" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Titre de la
                    session</label>
                <input type="text" name="title" id="title" class="form-input" required placeholder="Ex: Soirée Valorant"
                    style="width:100%">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="description" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Description
                    (optionnel)</label>
                <textarea name="description" id="description" class="form-input" rows="3"
                    placeholder="Détails sur la session..." style="width:100%"></textarea>
            </div>

            <div class="form-row" style="display:flex; gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="flex:1">
                    <label for="scheduled_at" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Date et
                        Heure</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-input" required
                        style="width:100%">
                </div>
                <div class="form-group" style="flex:1">
                    <label for="max_players" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Nb Max
                        Joueurs</label>
                    <input type="number" name="max_players" id="max_players" value="10" min="1" max="100"
                        class="form-input" style="width:100%">
                </div>
            </div>

            <div class="form-actions" style="display:flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <a href="/sessions" class="btn btn--secondary">Annuler</a>
                <button type="submit" class="btn btn--primary">Créer la session</button>
            </div>
        </form>
    </div>
</main>