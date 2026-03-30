<?php

namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller
{
    public function index()
    {
        // Simulation de données (SERA MIGRÉ VERS MODELS)
        $users = [
            ['id' => 1, 'name' => 'ProGamer123', 'email' => 'pro@gamevault.com', 'role' => 'Admin', 'status' => 'Actif'],
            ['id' => 2, 'name' => 'SoulsMaster', 'email' => 'souls@gmail.com', 'role' => 'Modérateur', 'status' => 'Actif'],
            ['id' => 3, 'name' => 'SoloPlayer', 'email' => 'solo@yahoo.fr', 'role' => 'Membre', 'status' => 'Banni'],
            ['id' => 4, 'name' => 'NightWanderer', 'email' => 'night@outlook.com', 'role' => 'Membre', 'status' => 'Actif'],
        ];

        return $this->render('admin/index', [
            'title' => 'Administration — GameVault',
            'users' => $users
        ]);
    }
}
