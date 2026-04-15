<?php

namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller
{
    private $userModel;
    private $gameModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\User();
        $this->gameModel = new \App\Models\Game();

        // Access Control
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
    }

    public function index()
    {
        $stats = $this->userModel->getGlobalStats();
        $users = $this->userModel->findAll();

        return $this->render('admin/index', [
            'title' => 'Administration — GameVault',
            'stats' => $stats,
            'users' => $users
        ]);
    }

    public function updateUser()
    {
        $id = $_POST['user_id'] ?? null;
        $action = $_POST['action'] ?? null;

        if ($id && $action) {
            if ($action === 'ban')
                $this->userModel->updateStatus($id, 'banned');
            if ($action === 'unban')
                $this->userModel->updateStatus($id, 'active');
            if ($action === 'promote')
                $this->userModel->updateRole($id, 'admin');
        }

        header('Location: /admin');
        exit;
    }

    public function updateGame()
    {
        $id = $_POST['game_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($id && $status) {
            $this->gameModel->update($id, ['status' => $status]);
        }

        header('Location: /admin');
        exit;
    }
}
