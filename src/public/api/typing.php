<?php

// Simple simulation of typing indicator
// In a real app, this would use Redis or a temp table in SQL

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Helpers\DbHelper;

header('Content-Type: application/json');

$db = DbHelper::getInstance()->getConnection();
$session_id = $_GET['session_id'] ?? null;
$user_id = $_GET['user_id'] ?? 1;
$is_typing = $_GET['typing'] ?? null;

// For this exercise, we'll use a simple file-based "cache" in /tmp
$cache_file = sys_get_temp_dir() . "/typing_session_$session_id.json";

$data = [];
if (file_exists($cache_file)) {
    $data = json_decode(file_get_contents($cache_file), true);
}

// Cleanup old entries (older than 5 seconds)
$now = time();
foreach ($data as $uid => $timestamp) {
    if ($now - $timestamp > 5) {
        unset($data[$uid]);
    }
}

if ($is_typing !== null) {
    if ($is_typing == '1') {
        $data[$user_id] = $now;
    } else {
        unset($data[$user_id]);
    }
    file_put_contents($cache_file, json_encode($data));
}

// Return list of other users typing
unset($data[$user_id]);
$typing_users = array_keys($data);

// Map IDs to names (hardcoded for now to keep it simple, or we could fetch from DB)
// For the demo, we'll just return the count or generic names
echo json_encode(['typing_count' => count($typing_users)]);
