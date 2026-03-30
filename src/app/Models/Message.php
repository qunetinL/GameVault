<?php

namespace App\Models;

use App\Core\Model;

class Message extends Model
{
    public function getBySession($sessionId)
    {
        return $this->query(
            "SELECT m.*, u.username as sender_name 
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
            "INSERT INTO messages (session_id, sender_id, content, created_at) VALUES (?, ?, ?, NOW())",
            [$data['session_id'], $data['sender_id'], $data['content']]
        );
    }
}
