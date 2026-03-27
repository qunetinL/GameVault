<?php
require_once __DIR__ . '/../../app/Helpers/DbHelper.php';

header('Content-Type: application/json');

$db = DbHelper::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $session_id = $_GET['session_id'] ?? null;

    if (!$session_id) {
        http_response_code(400);
        echo json_encode(['error' => 'session_id is required']);
        exit;
    }

    // Récupérer les votes groupés par jeu
    $stmt = $db->prepare("
        SELECT g.id as game_id, g.title, COUNT(v.id) as vote_count 
        FROM games g
        LEFT JOIN votes v ON g.id = v.game_id AND v.session_id = ?
        WHERE g.id IN (SELECT game_id FROM collections WHERE user_id IN (SELECT organizer_id FROM sessions WHERE id = ?))
           OR g.id IN (SELECT selected_game_id FROM sessions WHERE id = ?)
        GROUP BY g.id, g.title
    ");
    $stmt->execute([$session_id, $session_id, $session_id]);
    $votes = $stmt->fetchAll();

    echo json_encode($votes);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $session_id = $data['session_id'] ?? null;
    $user_id = $data['user_id'] ?? 1; // Simulation
    $game_id = $data['game_id'] ?? null;

    if (!$session_id || !$game_id) {
        http_response_code(400);
        echo json_encode(['error' => 'session_id and game_id are required']);
        exit;
    }

    // Supprimer le vote précédent de l'utilisateur pour cette session (un seul vote autorisé)
    $stmt = $db->prepare("DELETE FROM votes WHERE session_id = ? AND user_id = ?");
    $stmt->execute([$session_id, $user_id]);

    // Insérer le nouveau vote
    $stmt = $db->prepare("INSERT INTO votes (session_id, user_id, game_id) VALUES (?, ?, ?)");
    $stmt->execute([$session_id, $user_id, $game_id]);

    echo json_encode(['success' => true]);
}
