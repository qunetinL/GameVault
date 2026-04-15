<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Game;

class DashboardController extends Controller
{
    public function index()
    {
        $gameModel = new Game();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $userId = $_SESSION['user_id'];

        $stats = [
            'games_count' => 124,
            'sessions_played' => 450,
            'friends_online' => 85,
        ];

        $recentGames = $gameModel->query("SELECT * FROM games LIMIT 4")->fetchAll();

        return $this->render('dashboard/index', [
            'title' => 'Dashboard — GameVault',
            'stats' => $stats,
            'recentGames' => $recentGames
        ]);
    }
}
