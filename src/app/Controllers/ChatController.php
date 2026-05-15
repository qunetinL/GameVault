<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Message;
use App\Models\Session;
use App\Models\Store;

class ChatController extends Controller
{
    public function index()
    {
        $userModel = new User();
        $messageModel = new Message();
        $sessionModel = new Session();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $currentUser = $userModel->query("SELECT id, username FROM users WHERE id = ?", [$userId])->fetch();

        $sessionId = $_GET['session_id'] ?? null;

        // Charger les sessions disponibles pour l'utilisateur (comme contacts)
        $allSessions = $sessionModel->findAll();
        $contacts = [];
        foreach ($allSessions as $s) {
            $contacts[] = [
                'id' => $s['id'],
                'name' => $s['title'],
                'last_msg' => $s['organizer_name'],
                'time' => date('H:i', strtotime($s['scheduled_at'])),
                'online' => $s['status'] === 'planned' || $s['status'] === 'in_progress',
            ];
        }

        // Si pas de session_id, prendre la première disponible
        if (!$sessionId && !empty($contacts)) {
            $sessionId = $contacts[0]['id'];
        }

        // Si aucune session disponible, rediriger vers la liste
        if (!$sessionId) {
            header('Location: /sessions');
            exit;
        }

        // Charger la session courante
        $session = $sessionModel->find($sessionId);

        return $this->render('chat/index', [
            'title' => 'Chat — GameVault',
            'currentUser' => $currentUser,
            'contacts' => $contacts,
            'session_id' => $sessionId,
            'session' => $session,
            'bodyClass' => 'page-chat',
            'scripts' => ['/js/chat.js?v=3']
        ]);
    }
}
