<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Session extends Model
{
    public function create(array $data)
    {
        $sql = "INSERT INTO sessions (title, description, scheduled_at, max_players, status, organizer_id) 
                VALUES (:title, :description, :scheduled_at, :max_players, :status, :organizer_id)";

        $this->query($sql, [
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':scheduled_at' => $data['scheduled_at'],
            ':max_players' => $data['max_players'] ?? 10,
            ':status' => $data['status'] ?? 'planned',
            ':organizer_id' => $data['organizer_id']
        ]);

        return self::$db->lastInsertId();
    }

    public function findAll()
    {
        $sql = "SELECT s.*, u.username as organizer_name 
                FROM sessions s 
                JOIN users u ON s.organizer_id = u.id 
                ORDER BY s.scheduled_at DESC";
        return $this->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $sql = "SELECT s.*, u.username as organizer_name, g.title as selected_game_title 
                FROM sessions s 
                JOIN users u ON s.organizer_id = u.id 
                LEFT JOIN games g ON s.selected_game_id = g.id 
                WHERE s.id = ?";
        return $this->query($sql, [$id])->fetch();
    }

    public function getParticipants($sessionId)
    {
        $sql = "SELECT u.id, u.username, i.status 
                FROM users u 
                JOIN invitations i ON u.id = i.user_id 
                WHERE i.session_id = ?";
        return $this->query($sql, [$sessionId])->fetchAll();
    }

    public function inviteUser($sessionId, $userId)
    {
        $sql = "INSERT IGNORE INTO invitations (session_id, user_id, status) VALUES (?, ?, 'pending')";
        return $this->query($sql, [$sessionId, $userId]);
    }

    public function respondToInvitation($sessionId, $userId, $status)
    {
        $sql = "UPDATE invitations SET status = ? WHERE session_id = ? AND user_id = ?";
        return $this->query($sql, [$status, $sessionId, $userId]);
    }

    public function getVotes($sessionId)
    {
        $sql = "SELECT g.id, g.title, COUNT(v.id) as vote_count 
                FROM votes v 
                JOIN games g ON v.game_id = g.id 
                WHERE v.session_id = ? 
                GROUP BY g.id";
        return $this->query($sql, [$sessionId])->fetchAll();
    }

    public function castVote($sessionId, $userId, $gameId)
    {
        // One vote per user per session
        $sql = "INSERT INTO votes (session_id, user_id, game_id) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE game_id = VALUES(game_id)";
        return $this->query($sql, [$sessionId, $userId, $gameId]);
    }

    public function updateStatus($id, $status)
    {
        return $this->query("UPDATE sessions SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM sessions WHERE id = ?", [$id]);
    }
}
