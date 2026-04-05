<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Game;
use App\Models\Session;
use App\Models\Message;
use App\Models\User;

class ApiController extends Controller
{
    private $gameModel;
    private $sessionModel;
    private $messageModel;
    private $userModel;

    public function __construct()
    {
        $this->gameModel = new Game();
        $this->sessionModel = new Session();
        $this->messageModel = new Message();
        $this->userModel = new User();

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            $this->json(['error' => 'Unauthorized'], 401);
            exit;
        }
    }

    private function json($data, $status = 200)
    {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    // --- GAMES ---

    public function getGames()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $games = $this->gameModel->findAll($limit, $offset);
        $this->json($games);
    }

    public function getGame($id)
    {
        $game = $this->gameModel->find($id);
        if (!$game) {
            $this->json(['error' => 'Game not found'], 404);
        }
        $this->json($game);
    }

    public function createGame()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['title'])) {
            $this->json(['error' => 'Missing title'], 400);
        }

        $id = $this->gameModel->create($input);
        $this->json(['id' => $id, 'message' => 'Game created'], 201);
    }

    public function updateGame($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $game = $this->gameModel->find($id);
        if (!$game) {
            $this->json(['error' => 'Game not found'], 404);
        }

        $this->gameModel->update($id, $input);
        $this->json(['message' => 'Game updated']);
    }

    public function deleteGame($id)
    {
        $game = $this->gameModel->find($id);
        if (!$game) {
            $this->json(['error' => 'Game not found'], 404);
        }

        $this->gameModel->delete($id);
        $this->json(['message' => 'Game deleted'], 200);
    }

    public function searchGames()
    {
        $query = $_GET['q'] ?? '';
        $games = $this->gameModel->search($query);
        $this->json($games);
    }

    // --- SESSIONS ---

    public function getSessions()
    {
        $sessions = $this->sessionModel->findAll();
        $this->json($sessions);
    }

    // --- MESSAGES ---

    public function sendMessage()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['session_id']) || empty($input['content'])) {
            $this->json(['error' => 'Missing data'], 400);
        }

        $data = [
            'session_id' => $input['session_id'],
            'sender_id' => $_SESSION['user_id'],
            'content' => $input['content']
        ];

        $this->messageModel->create($data);
        $this->json(['message' => 'Message sent'], 201);
    }

    // --- STATS ---

    public function getStats()
    {
        $stats = $this->userModel->getStats($_SESSION['user_id']);
        $this->json($stats);
    }
}
