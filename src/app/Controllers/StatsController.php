<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\RedisHelper;
use App\Models\Game;
use App\Models\Session;

class StatsController extends Controller
{
    private $redis;
    private $gameModel;
    private $sessionModel;

    public function __construct()
    {
        $this->redis = RedisHelper::getInstance();
        $this->gameModel = new Game();
        $this->sessionModel = new Session();
    }

    public function index()
    {
        // 1. Genres Distribution (MySQL)
        $genresData = $this->gameModel->query(
            "SELECT t.name, COUNT(*) as count 
             FROM tags t 
             JOIN game_tags gt ON t.id = gt.tag_id 
             GROUP BY t.id"
        )->fetchAll();

        // 2. Sessions per Month (MySQL)
        $sessionsData = $this->sessionModel->query(
            "SELECT DATE_FORMAT(scheduled_at, '%Y-%m') as month, COUNT(*) as count 
             FROM sessions 
             WHERE scheduled_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY month 
             ORDER BY month ASC"
        )->fetchAll();

        // 3. Top Games by Views (Redis)
        $views = $this->redis->getGameViews();
        arsort($views);
        $topViews = array_slice($views, 0, 5, true);

        $topGames = [];
        foreach ($topViews as $id => $count) {
            $game = $this->gameModel->find($id);
            if ($game) {
                $topGames[] = [
                    'title' => $game['title'],
                    'views' => $count
                ];
            }
        }

        return $this->render('stats/index', [
            'title' => 'Statistiques — GameVault',
            'genres' => $genresData,
            'sessions' => $sessionsData,
            'topGames' => $topGames
        ]);
    }
}
