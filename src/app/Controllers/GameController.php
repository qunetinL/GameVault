<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Game;

class GameController extends Controller
{
    private $gameModel;

    public function __construct()
    {
        $this->gameModel = new Game();
        // Simple auth check for now
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index()
    {
        $query = $_GET['q'] ?? '';
        if ($query) {
            $games = $this->gameModel->search($query);
        } else {
            $games = $this->gameModel->findAll();
        }

        return $this->render('games/index', [
            'title' => 'Exploration — GameVault',
            'games' => $games,
            'query' => $query
        ]);
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        $game = $this->gameModel->find($id);
        if (!$game) {
            header('Location: /games');
            exit;
        }

        $inCollection = $this->gameModel->isInCollection($_SESSION['user_id'], $id);

        return $this->render('games/show', [
            'title' => $game['title'] . ' — GameVault',
            'game' => $game,
            'inCollection' => $inCollection
        ]);
    }

    public function create()
    {
        return $this->render('games/create', ['title' => 'Ajouter un jeu']);
    }

    public function store()
    {
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'release_date' => !empty($_POST['release_date']) ? $_POST['release_date'] : null,
            'rating' => !empty($_POST['rating']) ? (float) $_POST['rating'] : 0,
            'added_by' => $_SESSION['user_id']
        ];

        // Handle Image Upload (priorité au fichier uploadé)
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $data['cover_image'] = $this->handleUpload($_FILES['cover_image']);
        } elseif (!empty($_POST['cover_image_url'])) {
            // Télécharger l'image depuis RAWG
            $data['cover_image'] = $this->downloadCoverImage($_POST['cover_image_url']);
        }

        $gameId = $this->gameModel->create($data);

        if ($gameId) {
            // Lier les tags RAWG
            if (!empty($_POST['tags_rawg'])) {
                $this->linkTags($gameId, $_POST['tags_rawg']);
            }

            // Lier les plateformes RAWG
            if (!empty($_POST['platforms_rawg'])) {
                $this->linkPlatforms($gameId, $_POST['platforms_rawg']);
            }

            // Auto-add to creator's collection
            $this->gameModel->addToCollection($_SESSION['user_id'], $gameId);
        }

        header('Location: /game?id=' . $gameId);
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $game = $this->gameModel->find($id);
        if (!$game) {
            header('Location: /games');
            exit;
        }

        // Only the creator or an admin can edit
        if ($game['added_by'] != $_SESSION['user_id'] && ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /game?id=' . $id);
            exit;
        }

        return $this->render('games/edit', [
            'title' => 'Modifier ' . $game['title'],
            'game' => $game
        ]);
    }

    public function update()
    {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;

        $game = $this->gameModel->find($id);
        if (!$game) {
            header('Location: /games');
            exit;
        }

        // Only the creator or an admin can update
        if ($game['added_by'] != $_SESSION['user_id'] && ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /game?id=' . $id);
            exit;
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'release_date' => !empty($_POST['release_date']) ? $_POST['release_date'] : null,
            'rating' => !empty($_POST['rating']) ? (float) $_POST['rating'] : 0
        ];

        // Handle Image Upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $data['cover_image'] = $this->handleUpload($_FILES['cover_image']);
        } else {
            $data['cover_image'] = $game['cover_image'];
        }

        $this->gameModel->update($id, $data);
        header('Location: /game?id=' . $id);
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;

        $game = $this->gameModel->find($id);
        if (!$game) {
            header('Location: /games');
            exit;
        }

        // Only the creator or an admin can delete
        if ($game['added_by'] != $_SESSION['user_id'] && ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /game?id=' . $id);
            exit;
        }

        $this->gameModel->delete($id);
        header('Location: /games');
        exit;
    }

    public function toggleCollection()
    {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $userId = $_SESSION['user_id'];
        if ($this->gameModel->isInCollection($userId, $id)) {
            $this->gameModel->removeFromCollection($userId, $id);
            $status = 'removed';
        } else {
            $this->gameModel->addToCollection($userId, $id);
            $status = 'added';
        }

        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'action' => $status]);
            exit;
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $parsed = parse_url($referer);
        $safeDest = (isset($parsed['path']) && strpos($parsed['path'], '/') === 0 && !strpos($parsed['path'], '//') !== false)
            ? $parsed['path'] . (isset($parsed['query']) ? '?' . $parsed['query'] : '')
            : '/game?id=' . $id;
        header('Location: ' . $safeDest);
        exit;
    }

    private function downloadCoverImage(string $url): ?string
    {
        $allowedHosts = ['media.rawg.io'];
        $parsed = parse_url($url);
        if (!$parsed || !in_array($parsed['host'] ?? '', $allowedHosts)) {
            return null;
        }

        $imageData = @file_get_contents($url, false, stream_context_create([
            'http' => ['timeout' => 10, 'header' => 'User-Agent: GameVault/1.0'],
        ]));

        if ($imageData === false) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.jpg';
        $targetFile = $uploadDir . $filename;

        if (file_put_contents($targetFile, $imageData) !== false) {
            return '/uploads/covers/' . $filename;
        }

        return null;
    }

    private function linkTags(int $gameId, string $tagsRawg): void
    {
        $tagNames = array_map('trim', explode(',', $tagsRawg));
        foreach ($tagNames as $name) {
            if (empty($name)) continue;
            $this->gameModel->linkTag($gameId, $name);
        }
    }

    private function linkPlatforms(int $gameId, string $platformsRawg): void
    {
        $platformNames = array_map('trim', explode(',', $platformsRawg));
        foreach ($platformNames as $name) {
            if (empty($name)) continue;
            $this->gameModel->linkPlatform($gameId, $name);
        }
    }

    private function handleUpload($file)
    {
        // 1. Verify MIME type (Security Hardening)
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!in_array($mime, $allowedMimes)) {
            return null; // Rejected
        }

        // 2. Process valid upload
        $uploadDir = __DIR__ . '/../../public/uploads/covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $extension = $mimeToExt[$mime];
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/uploads/covers/' . $filename;
        }

        return null;
    }
}
