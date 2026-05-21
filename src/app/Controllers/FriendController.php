<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Friendship;

class FriendController extends Controller
{
    private $friendModel;

    public function __construct()
    {
        $this->friendModel = new Friendship();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function request()
    {
        $receiverId = (int) ($_POST['receiver_id'] ?? 0);
        $userId = $_SESSION['user_id'];

        if ($receiverId > 0) {
            $this->friendModel->sendRequest($userId, $receiverId);
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/users';
        $parsed = parse_url($referer);
        $safe = (isset($parsed['path']) && strpos($parsed['path'], '/') === 0)
            ? $parsed['path'] . (isset($parsed['query']) ? '?' . $parsed['query'] : '')
            : '/users';
        header('Location: ' . $safe);
        exit;
    }

    public function respond()
    {
        $friendshipId = (int) ($_POST['friendship_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $userId = $_SESSION['user_id'];

        if ($friendshipId > 0 && in_array($status, ['accepted', 'rejected'])) {
            $this->friendModel->respond($friendshipId, $userId, $status);
        }

        header('Location: /profile');
        exit;
    }

    public function remove()
    {
        $friendId = (int) ($_POST['friend_id'] ?? 0);
        $userId = $_SESSION['user_id'];

        if ($friendId > 0) {
            $this->friendModel->remove($userId, $friendId);
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/profile';
        $parsed = parse_url($referer);
        $safe = (isset($parsed['path']) && strpos($parsed['path'], '/') === 0)
            ? $parsed['path'] . (isset($parsed['query']) ? '?' . $parsed['query'] : '')
            : '/profile';
        header('Location: ' . $safe);
        exit;
    }

    public function pendingCount()
    {
        header('Content-Type: application/json');
        $count = $this->friendModel->getPendingCount($_SESSION['user_id']);
        echo json_encode(['count' => $count]);
        exit;
    }
}
