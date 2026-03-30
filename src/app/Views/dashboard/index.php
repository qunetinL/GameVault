<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Tableau de bord</h1>
            <p>Aperçu de votre activité gaming et statistiques.</p>
        </div>
    </header>

    <div class="stats-grid"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <div class="card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <h3>Jeux possédés</h3>
            <p style="font-size: 2rem; font-weight: bold;">
                <?= $stats['games_count'] ?>
            </p>
        </div>
        <div class="card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <h3>Sessions jouées</h3>
            <p style="font-size: 2rem; font-weight: bold;">
                <?= $stats['sessions_played'] ?>
            </p>
        </div>
        <div class="card"
            style="padding: 1.5rem; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
            <h3>Amis en ligne</h3>
            <p style="font-size: 2rem; font-weight: bold;">
                <?= $stats['friends_online'] ?>
            </p>
        </div>
    </div>

    <div
        style="margin-top: 3rem; background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border);">
        <h2>Activités récentes</h2>
        <p style="opacity: 0.7;">Vous avez rejoint la session "Co-op Boss Malenia" il y a 2 heures.</p>
    </div>
</main>