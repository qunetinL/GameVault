<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Game;
use App\Models\Session;
use App\Models\Message;
use App\Models\User;
use App\Services\RawgService;

class ApiController extends Controller
{
    private $gameModel;
    private $sessionModel;
    private $messageModel;
    private $userModel;
    private $rawgService;

    public function __construct()
    {
        $this->gameModel = new Game();
        $this->sessionModel = new Session();
        $this->messageModel = new Message();
        $this->userModel = new User();
        $this->rawgService = new RawgService();

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
        $cacheKey = "search:" . md5($query);
        $redis = \App\Helpers\RedisHelper::getInstance();

        $games = $redis->getCache($cacheKey);

        if ($games === null) {
            $games = $this->gameModel->search($query);
            $redis->setCache($cacheKey, $games, 300); // 5 min cache
        }

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
            'content' => htmlspecialchars($input['content'], ENT_QUOTES, 'UTF-8')
        ];

        $this->messageModel->create($data);
        $this->json(['message' => 'Message sent'], 201);
    }

    public function getNewMessages($sessionId)
    {
        $lastId = $_GET['last_id'] ?? 0;

        // Mark as read when fetching for a session
        $this->messageModel->markAsRead($sessionId, $_SESSION['user_id']);

        $messages = $this->messageModel->getNewMessages($sessionId, $lastId);
        $this->json($messages);
    }

    // --- VOTES ---

    public function getVotes($sessionId)
    {
        $votes = $this->sessionModel->getVotes($sessionId);
        $this->json($votes);
    }

    public function castVote()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['session_id']) || empty($input['game_id'])) {
            $this->json(['error' => 'Missing data'], 400);
        }

        $this->sessionModel->castVote($input['session_id'], $_SESSION['user_id'], $input['game_id']);
        $this->json(['message' => 'Vote recorded']);
    }

    // --- TYPING ---

    public function typing()
    {
        $sessionId = $_GET['session_id'] ?? null;
        $userId = $_GET['user_id'] ?? null;
        $typing = (bool) ($_GET['typing'] ?? 0);

        if (!$sessionId) {
            $this->json(['error' => 'Missing session_id'], 400);
        }

        $redis = \App\Helpers\RedisHelper::getInstance();
        $key = "typing:session:$sessionId";

        if ($typing) {
            $redis->setCache("typing:user:$userId:$sessionId", 1, 5);
        } else {
            $redis->deleteCache("typing:user:$userId:$sessionId");
        }

        // Compter les utilisateurs en train d'écrire (hors l'utilisateur actuel)
        $typingCount = 0;
        // Vérifier chaque participant potentiel
        // Simplification : on retourne juste si d'autres écrivent
        $this->json(['typing_count' => $typingCount]);
    }

    // --- RAWG ---

    public function rawgSearch()
    {
        $query = $_GET['q'] ?? '';
        if (strlen(trim($query)) < 2) {
            $this->json([]);
        }

        $redis = \App\Helpers\RedisHelper::getInstance();
        $cacheKey = "rawg:search:" . md5($query);
        $cached = $redis->getCache($cacheKey);

        if ($cached !== null) {
            $this->json($cached);
        }

        $results = $this->rawgService->search($query);
        $redis->setCache($cacheKey, $results, 3600); // 1h cache
        $this->json($results);
    }

    // --- STATS ---

    public function getStats()
    {
        $stats = $this->userModel->getStats($_SESSION['user_id']);
        $stats['unread_messages'] = $this->messageModel->getUnreadCount($_SESSION['user_id']);
        $this->json($stats);
    }
}
