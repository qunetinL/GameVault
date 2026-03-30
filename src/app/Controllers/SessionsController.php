<?php

namespace App\Controllers;

use App\Core\Controller;

class SessionsController extends Controller
{
    public function index()
    {
        // Simulation de données (SERA MIGRÉ VERS MODELS)
        $sessions = [
            [
                'game' => 'Valorant',
                'title' => 'Tournoi Amical 5v5',
                'date' => 'Ce soir, 20:00',
                'players' => '3/5',
                'level' => 'Intermédiaire',
                'host' => 'ProGamer123',
                'img' => '/assets/cyberpunk.jpeg'
            ],
            [
                'game' => 'Cyberpunk 2077',
                'title' => 'Découverte DLC Phantom Liberty',
                'date' => 'Demain, 14:00',
                'players' => '1/1',
                'level' => 'Tous niveaux',
                'host' => 'SoloPlayer',
                'img' => '/assets/cyberpunk.jpeg'
            ],
            [
                'game' => 'Elden Ring',
                'title' => 'Co-op Boss Malenia',
                'date' => '22 Mars, 21:00',
                'players' => '2/3',
                'level' => 'Avancé',
                'host' => 'SoulsMaster',
                'img' => '/assets/cyberpunk.jpeg'
            ]
        ];

        return $this->render('sessions/index', [
            'title' => 'Sessions Gaming — GameVault',
            'sessions' => $sessions
        ]);
    }
}
