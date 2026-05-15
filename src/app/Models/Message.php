<?php

namespace App\Models;

use App\Core\Model;

class Message extends Model
{
    public function getBySession($sessionId)
    {
        return $this->query(
            "SELECT m.*, u.username 
             FROM messages m 
             JOIN users u ON m.sender_id = u.id 
             WHERE m.session_id = ? 
             ORDER BY m.created_at ASC",
            [$sessionId]
        )->fetchAll();
    }

    public function create($data)
    {
        return $this->query(
            "INSERT INTO messages (session_id, sender_id, content, created_at, is_read) VALUES (?, ?, ?, NOW(), 0)",
            [$data['session_id'], $data['sender_id'], $data['content']]
        );
    }

    public function getNewMessages($sessionId, $lastId)
    {
        return $this->query(
            "SELECT m.*, u.username,
                    (SELECT GROUP_CONCAT(s.name ORDER BY s.name SEPARATOR ', ')
                     FROM user_stores us
                     JOIN stores s ON us.store_id = s.id
                     WHERE us.user_id = m.sender_id) as user_stores
             FROM messages m
             JOIN users u ON m.sender_id = u.id
             WHERE m.session_id = ? AND m.id > ?
             ORDER BY m.created_at ASC",
            [$sessionId, $lastId]
        )->fetchAll();
    }

    public function markAsRead($sessionId, $userId)
    {
        return $this->query(
            "UPDATE messages SET is_read = 1 
             WHERE session_id = ? AND sender_id != ? AND is_read = 0",
            [$sessionId, $userId]
        );
    }

    public function getUnreadCount($userId)
    {
        return $this->query(
            "SELECT COUNT(*) FROM messages 
             WHERE sender_id != ? AND is_read = 0",
            [$userId]
        )->fetchColumn();
    }
}
