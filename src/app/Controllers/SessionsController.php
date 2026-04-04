<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Session;
use App\Models\Game;
use App\Models\User;

class SessionsController extends Controller
{
    private $sessionModel;
    private $gameModel;

    public function __construct()
    {
        $this->sessionModel = new Session();
        $this->gameModel = new Game();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index()
    {
        $sessions = $this->sessionModel->findAll();

        return $this->render('sessions/index', [
            'title' => 'Sessions Gaming — GameVault',
            'sessions' => $sessions
        ]);
    }

    public function create()
    {
        return $this->render('sessions/create', [
            'title' => 'Créer une session — GameVault'
        ]);
    }

    public function store()
    {
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'scheduled_at' => $_POST['scheduled_at'] ?? null,
            'max_players' => $_POST['max_players'] ?? 10,
            'status' => 'planned',
            'organizer_id' => $_SESSION['user_id']
        ];

        // Validation simple
        if (empty($data['title']) || empty($data['scheduled_at'])) {
            return $this->create();
        }

        if (strtotime($data['scheduled_at']) < time()) {
            return $this->create();
        }

        $sessionId = $this->sessionModel->create($data);

        // L'organisateur est automatiquement inscrit
        $this->sessionModel->inviteUser($sessionId, $_SESSION['user_id']);
        $this->sessionModel->respondToInvitation($sessionId, $_SESSION['user_id'], 'accepted');

        header('Location: /session/show?id=' . $sessionId);
        exit;
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        $session = $this->sessionModel->find($id);

        if (!$session) {
            header('Location: /sessions');
            exit;
        }

        $participants = $this->sessionModel->getParticipants($id);
        $votes = $this->sessionModel->getVotes($id);
        $games = $this->gameModel->findAll(); // Pour proposer des jeux au vote

        return $this->render('sessions/show', [
            'title' => $session['title'] . ' — GameVault',
            'session' => $session,
            'participants' => $participants,
            'votes' => $votes,
            'games' => $games
        ]);
    }

    public function invite()
    {
        $sessionId = $_POST['session_id'] ?? null;
        $username = $_POST['username'] ?? null;

        $userModel = new User();
        $user = $userModel->findByUsername($username); // À implémenter dans User.php

        if ($user) {
            $this->sessionModel->inviteUser($sessionId, $user['id']);
        }

        header('Location: /session/show?id=' . $sessionId);
        exit;
    }

    public function respond()
    {
        $sessionId = $_POST['session_id'] ?? null;
        $status = $_POST['status'] ?? 'pending';

        $this->sessionModel->respondToInvitation($sessionId, $_SESSION['user_id'], $status);

        header('Location: /session/show?id=' . $sessionId);
        exit;
    }

    public function vote()
    {
        $sessionId = $_POST['session_id'] ?? null;
        $gameId = $_POST['game_id'] ?? null;

        $this->sessionModel->castVote($sessionId, $_SESSION['user_id'], $gameId);

        header('Location: /session/show?id=' . $sessionId);
        exit;
    }
}