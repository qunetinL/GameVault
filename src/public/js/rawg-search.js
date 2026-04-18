/**
 * RAWG.io — Recherche et auto-remplissage pour le formulaire d'ajout de jeu
 */
(function () {
    const searchInput = document.getElementById('rawg-search');
    const resultsContainer = document.getElementById('rawg-results');
    if (!searchInput || !resultsContainer) return;

    let debounceTimer = null;

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 2) {
            resultsContainer.innerHTML = '';
            resultsContainer.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => fetchResults(query), 400);
    });

    // Fermer les résultats quand on clique ailleurs
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.rawg-search-wrapper')) {
            resultsContainer.style.display = 'none';
        }
    });

    async function fetchResults(query) {
        try {
            const res = await fetch('/api/rawg/search?q=' + encodeURIComponent(query));
            if (!res.ok) return;
            const games = await res.json();
            renderResults(games);
        } catch (err) {
            console.error('RAWG search error:', err);
        }
    }

    function renderResults(games) {
        if (!games.length) {
            resultsContainer.innerHTML = '<div class="rawg-result-item rawg-no-result">Aucun résultat trouvé</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        resultsContainer.innerHTML = games.map(game => `
            <div class="rawg-result-item" data-game='${JSON.stringify(game).replace(/'/g, "&#39;")}'>
                <img src="${game.cover_image || ''}" alt="" class="rawg-result-img" onerror="this.style.display='none'">
                <div class="rawg-result-info">
                    <strong>${escapeHtml(game.title)}</strong>
                    <span class="rawg-result-meta">
                        ${game.release_date ? game.release_date.substring(0, 4) : ''}
                        ${game.rating ? ' · ' + game.rating + '/10' : ''}
                    </span>
                    <span class="rawg-result-tags">${(game.tags || []).join(', ')}</span>
                </div>
            </div>
        `).join('');

        resultsContainer.style.display = 'block';

        // Bind click events
        resultsContainer.querySelectorAll('.rawg-result-item[data-game]').forEach(item => {
            item.addEventListener('click', function () {
                const game = JSON.parse(this.dataset.game);
                fillForm(game);
                resultsContainer.style.display = 'none';
                searchInput.value = game.title;
            });
        });
    }

    function fillForm(game) {
        setField('title', game.title);
        setField('description', game.description);
        setField('release_date', game.release_date || '');
        setField('rating', game.rating || '');

        // Cover image URL from RAWG
        const coverUrlInput = document.getElementById('cover_image_url');
        if (coverUrlInput) {
            coverUrlInput.value = game.cover_image || '';
        }

        // Preview image
        const preview = document.getElementById('cover-preview');
        if (preview && game.cover_image) {
            preview.src = game.cover_image;
            preview.style.display = 'block';
        }

        // Platforms
        const platformsInput = document.getElementById('platforms_rawg');
        if (platformsInput) {
            platformsInput.value = (game.platforms || []).join(', ');
        }

        // Tags/Genres
        const tagsInput = document.getElementById('tags_rawg');
        if (tagsInput) {
            tagsInput.value = (game.tags || []).join(', ');
        }
    }

    function setField(name, value) {
        const el = document.getElementById(name);
        if (el) el.value = value;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
})();
