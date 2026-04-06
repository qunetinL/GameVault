<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Tableau de bord Statistiques</h1>
            <p>Aperçu des performances et de l'activité de la communauté.</p>
        </div>
    </header>

    <div class="layout-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-top: 2rem;">

        <!-- Genres Distribution -->
        <div class="card" style="padding: 2rem;">
            <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem;">Distribution par Genre</h2>
            <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                <canvas id="genresChart"></canvas>
            </div>
        </div>

        <!-- Sessions per Month -->
        <div class="card" style="padding: 2rem;">
            <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem;">Sessions de Jeu (6 derniers mois)</h2>
            <div style="height: 300px;">
                <canvas id="sessionsChart"></canvas>
            </div>
        </div>

        <!-- Top Games -->
        <div class="card" style="padding: 2rem; grid-column: span 2;">
            <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem;">Top 5 des Jeux les plus consultés</h2>
            <div style="height: 300px;">
                <canvas id="topGamesChart"></canvas>
            </div>
        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Genres Chart
        const genresCtx = document.getElementById('genresChart').getContext('2d');
        const genresData = <?= json_encode($genres) ?>;

        new Chart(genresCtx, {
            type: 'doughnut',
            data: {
                labels: genresData.map(g => g.name),
                datasets: [{
                    data: genresData.map(g => g.count),
                    backgroundColor: [
                        '#8B5CF6', '#EC4899', '#10B981', '#F59E0B', '#3B82F6', '#6366F1'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { color: '#94a3b8' } }
                }
            }
        });

        // 2. Sessions Chart
        const sessionsCtx = document.getElementById('sessionsChart').getContext('2d');
        const sessionsData = <?= json_encode($sessions) ?>;

        new Chart(sessionsCtx, {
            type: 'line',
            data: {
                labels: sessionsData.map(s => s.month),
                datasets: [{
                    label: 'Sessions',
                    data: sessionsData.map(s => s.count),
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    x: { ticks: { color: '#94a3b8' }, grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 3. Top Games Chart
        const topGamesCtx = document.getElementById('topGamesChart').getContext('2d');
        const topGamesData = <?= json_encode($topGames) ?>;

        new Chart(topGamesCtx, {
            type: 'bar',
            data: {
                labels: topGamesData.map(g => g.title),
                datasets: [{
                    label: 'Vues',
                    data: topGamesData.map(g => g.views),
                    backgroundColor: '#10B981',
                    borderRadius: 8
                }]
            },
            options: {
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: { beginAtZero: true, ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    });
</script>