<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Store;
use App\Models\Friendship;

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

        $friendModel = new Friendship();
        $currentUserId = $_SESSION['user_id'];
        $friendStatus = ($currentUserId != $id) ? $friendModel->getStatus($currentUserId, (int) $id) : null;
        $isSender = ($friendStatus === 'pending') ? $friendModel->isSender($currentUserId, (int) $id) : false;
        $friendshipId = null;
        if ($friendStatus === 'pending' && !$isSender) {
            $row = $friendModel->query(
                "SELECT id FROM friendships WHERE sender_id = ? AND receiver_id = ? AND status = 'pending'",
                [(int) $id, $currentUserId]
            )->fetch();
            $friendshipId = $row ? $row['id'] : null;
        }

        return $this->render('users/show', [
            'title' => $user['username'] . ' — GameVault',
            'profileUser' => $user,
            'userStores' => $userStores,
            'games' => $games,
            'friendStatus' => $friendStatus,
            'isSender' => $isSender,
            'friendshipId' => $friendshipId,
        ]);
    }
}
