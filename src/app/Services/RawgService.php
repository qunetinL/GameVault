<?php

namespace App\Services;

class RawgService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.rawg.io/api';

    public function __construct()
    {
        $this->apiKey = $_ENV['RAWG_API_KEY'] ?? '';
    }

    public function search(string $query, int $pageSize = 10): array
    {
        if (empty($this->apiKey) || empty(trim($query))) {
            return [];
        }

        $url = $this->baseUrl . '/games?' . http_build_query([
            'key' => $this->apiKey,
            'search' => $query,
            'page_size' => $pageSize,
            'search_precise' => true,
        ]);

        $response = $this->fetch($url);
        if (!$response || !isset($response['results'])) {
            return [];
        }

        return array_map(function ($game) {
            return [
                'rawg_id' => $game['id'],
                'title' => $game['name'],
                'description' => strip_tags($game['description_raw'] ?? ''),
                'release_date' => $game['released'] ?? null,
                'rating' => $game['rating'] ? round($game['rating'] * 2, 1) : null, // RAWG /5 -> /10
                'cover_image' => $game['background_image'] ?? null,
                'platforms' => array_map(fn($p) => $p['platform']['name'], $game['platforms'] ?? []),
                'tags' => array_map(fn($t) => $t['name'], array_slice($game['genres'] ?? [], 0, 5)),
            ];
        }, $response['results']);
    }

    public function getGame(int $rawgId): ?array
    {
        if (empty($this->apiKey)) {
            return null;
        }

        $url = $this->baseUrl . '/games/' . $rawgId . '?' . http_build_query([
            'key' => $this->apiKey,
        ]);

        $game = $this->fetch($url);
        if (!$game || !isset($game['id'])) {
            return null;
        }

        return [
            'rawg_id' => $game['id'],
            'title' => $game['name'],
            'description' => strip_tags($game['description_raw'] ?? $game['description'] ?? ''),
            'release_date' => $game['released'] ?? null,
            'rating' => $game['rating'] ? round($game['rating'] * 2, 1) : null,
            'cover_image' => $game['background_image'] ?? null,
            'platforms' => array_map(fn($p) => $p['platform']['name'], $game['platforms'] ?? []),
            'tags' => array_map(fn($t) => $t['name'], array_slice($game['genres'] ?? [], 0, 5)),
        ];
    }

    private function fetch(string $url): ?array
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'header' => 'User-Agent: GameVault/1.0',
            ],
        ]);

        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            return null;
        }

        return json_decode($response, true);
    }
}
