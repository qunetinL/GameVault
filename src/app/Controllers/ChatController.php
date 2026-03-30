<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {
        $userModel = new User();
        $messageModel = new Message();

        $userId = $_SESSION['user_id'] ?? 1;
        $currentUser = $userModel->query("SELECT id, username FROM users WHERE id = ?", [$userId])->fetch();

        $sessionId = $_GET['session_id'] ?? 1;

        // Simulation de contacts
        $contacts = [
            ['id' => 1, 'name' => 'AlexGamer', 'last_msg' => 'Prêt pour demain ?', 'time' => '10:30', 'online' => true],
            ['id' => 2, 'name' => 'SarahStream', 'last_msg' => 'Carrément !', 'time' => 'Hier', 'online' => true],
        ];

        return $this->render('chat/index', [
            'title' => 'Chat — GameVault',
            'currentUser' => $currentUser,
            'contacts' => $contacts,
            'session_id' => $sessionId,
            'scripts' => ['/js/chat.js?v=2']
        ]);
    }
}