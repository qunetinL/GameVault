document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-games');
    const genreFilter = document.getElementById('filter-genre');
    const gamesGrid = document.getElementById('games-grid');

    if (!searchInput || !gamesGrid) return;

    const sortSelect = document.getElementById('sort-games');
    let currentGames = [];

    // Fonction de rendu des cartes
    const renderGameCards = (games) => {
        currentGames = games;
        gamesGrid.innerHTML = '';

        if (games.length === 0) {
            gamesGrid.innerHTML = '<div class="no-results">Aucun jeu trouvé...</div>';
            return;
        }

        games.forEach(game => {
            const card = document.createElement('article');
            card.className = 'game-card';
            card.innerHTML = `
                <div class="game-card__cover">
                    <img src="${game.img}" alt="${game.title}" loading="lazy" class="lazy-load">
                    <button class="btn-delete" data-id="${game.id}">×</button>
                    <div class="game-card__rating-badge">★ ${game.rating}</div>
                </div>
                <div class="game-card__body">
                    <h3 class="game-card__title">${game.title}</h3>
                    <p class="game-card__genre">${game.genre} • ${game.platform}</p>
                </div>
            `;
            gamesGrid.appendChild(card);
        });

        // Attach delete event
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const id = btn.dataset.id;
                window.Modal.confirm('Supprimer ce jeu ?', 'Cette action est irréversible.', () => {
                    window.Toast.show('Jeu supprimé de la collection !', 'success');
                    btn.closest('.game-card').style.opacity = '0';
                    setTimeout(() => btn.closest('.game-card').remove(), 300);
                });
            });
        });
    };

    // Tri dynamique
    sortSelect?.addEventListener('change', () => {
        const val = sortSelect.value;
        const sorted = [...currentGames].sort((a, b) => {
            if (val === 'name') return a.title.localeCompare(b.title);
            if (val === 'rating') return b.rating - a.rating;
            return 0;
        });
        renderGameCards(sorted);
    });

    // Fonction de recherche AJAX
    const fetchGames = async () => {
        const query = searchInput.value;
        const genre = genreFilter ? genreFilter.value : 'Tous les genres';

        try {
            const response = await fetch(`/api/games.php?q=${encodeURIComponent(query)}&genre=${encodeURIComponent(genre)}`);
            const games = await response.json();
            renderGameCards(games);
        } catch (error) {
            console.error('Erreur lors de la récupération des jeux:', error);
        }
    };

    // Écouteurs d'événements
    let timeout = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(fetchGames, 300); // Debounce de 300ms
    });

    if (genreFilter) {
        genreFilter.addEventListener('change', fetchGames);
    }
});
