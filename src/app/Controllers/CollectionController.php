<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\DbHelper;
use App\Models\Game;

class CollectionController extends Controller
{
    public function index()
    {
        $gameModel = new Game();
        $userId = $_SESSION['user_id'];

        $games = $gameModel->getByUserCollection($userId);
        $genres = ['Tous les genres', 'Action', 'RPG', 'Action RPG', 'FPS', 'Sandbox'];
        $platforms = ['Toutes les plateformes', 'PC', 'PS5', 'PS4', 'Switch', 'Xbox', 'Multi'];

        return $this->render('collection/index', [
            'title' => 'Ma Collection — GameVault',
            'games' => $games,
            'genres' => $genres,
            'platforms' => $platforms,
            'scripts' => ['/js/games.js']
        ], 'main');
    }
}
