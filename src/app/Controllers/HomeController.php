<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Simulation de données découverte (Sera migré vers Models)
        $popularGames = [
            ['id' => 2, 'title' => 'Elden Ring', 'img' => '/assets/cyberpunk.jpeg', 'rating' => 5],
            ['id' => 5, 'title' => 'Minecraft', 'img' => '/assets/minecraft.jpeg', 'rating' => 4.6],
        ];

        $nextSessions = [
            ['title' => 'Tournoi Valorant', 'date' => 'Ce soir, 20:00', 'host' => 'ProGamer123'],
        ];

        return $this->render('home/index', [
            'title' => 'Bienvenue sur GameVault',
            'popularGames' => $popularGames,
            'nextSessions' => $nextSessions
        ]);
    }
}
