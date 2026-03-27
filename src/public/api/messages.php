<?php
require_once __DIR__ . '/../../app/Helpers/DbHelper.php';

header('Content-Type: application/json');

$db = DbHelper::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $session_id = $_GET['session_id'] ?? null;
    $last_id = $_GET['last_id'] ?? 0;

    if (!$session_id) {
        http_response_code(400);
        echo json_encode(['error' => 'session_id is required']);
        exit;
    }

    $stmt = $db->prepare("
        SELECT m.*, u.username, u.avatar 
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE m.session_id = ? AND m.id > ?
        ORDER BY m.created_at ASC
    ");
    $stmt->execute([$session_id, $last_id]);
    $messages = $stmt->fetchAll();

    echo json_encode($messages);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $session_id = $data['session_id'] ?? null;
    $sender_id = $data['sender_id'] ?? 1; // Simulation: utilisateur 1 par défaut
    $content = $data['content'] ?? '';

    if (!$session_id || empty($content)) {
        http_response_code(400);
        echo json_encode(['error' => 'session_id and content are required']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO messages (session_id, sender_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$session_id, $sender_id, $content]);

    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
}
