<?php
header('Content-Type: application/json');

// Simulation de base de données de jeux
$allGames = [
    [
        'id' => 1,
        'title' => 'Cyberpunk 2077',
        'genre' => 'RPG',
        'platform' => 'PC',
        'rating' => 4.5,
        'img' => '/assets/cyberpunk.jpeg'
    ],
    [
        'id' => 2,
        'title' => 'Elden Ring',
        'genre' => 'Action-RPG',
        'platform' => 'PS5',
        'rating' => 4.9,
        'img' => '/assets/elden_ring.jpeg'
    ],
    [
        'id' => 3,
        'title' => 'Valorant',
        'genre' => 'FPS',
        'platform' => 'PC',
        'rating' => 4.2,
        'img' => '/assets/valorant.jpeg'
    ],
    [
        'id' => 4,
        'title' => 'The Witcher 3',
        'genre' => 'RPG',
        'platform' => 'PC',
        'rating' => 4.8,
        'img' => '/assets/witcher3.jpeg'
    ]
];

$query = $_GET['q'] ?? '';
$genre = $_GET['genre'] ?? 'Tous les genres';

$filteredGames = array_filter($allGames, function ($game) use ($query, $genre) {
    $matchesQuery = empty($query) || stripos($game['title'], $query) !== false;
    $matchesGenre = $genre === 'Tous les genres' || $game['genre'] === $genre;
    return $matchesQuery && $matchesGenre;
});

echo json_encode(array_values($filteredGames));
