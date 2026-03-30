<?php

namespace App\Controllers;

use App\Core\Controller;

class GameController extends Controller
{
    public function index()
    {
        $gameId = $_GET['id'] ?? null;

        if (!$gameId) {
            header('Location: /collection');
            exit;
        }

        // Simulation de données (SERA MIGRÉ VERS MODELS)
        $game = [
            'id' => $gameId,
            'title' => 'Elden Ring',
            'description' => 'Un jeu d\'action-RPG épique dans un monde de dark fantasy.',
            'genre' => 'Action RPG',
            'platform' => 'PS5',
            'rating' => 5.0,
            'img' => '/assets/cyberpunk.jpeg'
        ];

        return $this->render('game/index', [
            'title' => $game['title'] . ' — GameVault',
            'game' => $game
        ]);
    }
}
