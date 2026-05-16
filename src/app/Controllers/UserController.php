<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Store;

class UserController extends Controller
{
    private $userModel;
    private $gameModel;
    private $storeModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->gameModel = new Game();
        $this->storeModel = new Store();
    }

    public function index()
    {
        $users = $this->userModel->findAll();

        // Enrich each user with collection count and stores
        foreach ($users as &$user) {
            $user['game_count'] = $this->userModel->query(
                "SELECT COUNT(*) FROM collections WHERE user_id = ?",
                [$user['id']]
            )->fetchColumn();
            $user['stores'] = $this->storeModel->getUserStores($user['id']);
        }

        return $this->render('users/index', [
            'title' => 'Membres — GameVault',
            'users' => $users,
        ]);
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /users');
            exit;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            header('Location: /users');
            exit;
        }

        $userStores = $this->storeModel->getUserStores($id);
        $games = $this->gameModel->getByUserCollection($id);

        return $this->render('users/show', [
            'title' => $user['username'] . ' — GameVault',
            'profileUser' => $user,
            'userStores' => $userStores,
            'games' => $games,
        ]);
    }
}
