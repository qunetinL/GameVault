<?php

namespace App\Models;

use App\Core\Model;

class Friendship extends Model
{
    public function sendRequest(int $senderId, int $receiverId): bool
    {
        // Ne pas s'ajouter soi-meme
        if ($senderId === $receiverId) return false;

        // Verifier qu'il n'y a pas deja une relation (dans un sens ou l'autre)
        $existing = $this->getStatus($senderId, $receiverId);
        if ($existing !== null) return false;

        $this->query(
            "INSERT INTO friendships (sender_id, receiver_id, status) VALUES (?, ?, 'pending')",
            [$senderId, $receiverId]
        );
        return true;
    }

    public function respond(int $friendshipId, int $userId, string $status): bool
    {
        // Seul le receiver peut repondre
        $friendship = $this->query(
            "SELECT * FROM friendships WHERE id = ? AND receiver_id = ? AND status = 'pending'",
            [$friendshipId, $userId]
        )->fetch();

        if (!$friendship) return false;

        $this->query(
            "UPDATE friendships SET status = ? WHERE id = ?",
            [$status, $friendshipId]
        );
        return true;
    }

    public function remove(int $userId, int $friendId): bool
    {
        $this->query(
            "DELETE FROM friendships WHERE status = 'accepted'
             AND ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))",
            [$userId, $friendId, $friendId, $userId]
        );
        return true;
    }

    public function getFriends(int $userId): array
    {
        return $this->query(
            "SELECT u.id, u.username, u.created_at, f.created_at as friends_since
             FROM friendships f
             JOIN users u ON (u.id = CASE WHEN f.sender_id = ? THEN f.receiver_id ELSE f.sender_id END)
             WHERE f.status = 'accepted'
             AND (f.sender_id = ? OR f.receiver_id = ?)
             ORDER BY u.username ASC",
            [$userId, $userId, $userId]
        )->fetchAll();
    }

    public function getPendingRequests(int $userId): array
    {
        return $this->query(
            "SELECT f.id as friendship_id, u.id as user_id, u.username, f.created_at
             FROM friendships f
             JOIN users u ON u.id = f.sender_id
             WHERE f.receiver_id = ? AND f.status = 'pending'
             ORDER BY f.created_at DESC",
            [$userId]
        )->fetchAll();
    }

    public function getPendingCount(int $userId): int
    {
        return (int) $this->query(
            "SELECT COUNT(*) FROM friendships WHERE receiver_id = ? AND status = 'pending'",
            [$userId]
        )->fetchColumn();
    }

    public function getStatus(int $userId1, int $userId2): ?string
    {
        $row = $this->query(
            "SELECT status FROM friendships
             WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)",
            [$userId1, $userId2, $userId2, $userId1]
        )->fetch();

        return $row ? $row['status'] : null;
    }

    public function isSender(int $userId1, int $userId2): bool
    {
        $row = $this->query(
            "SELECT sender_id FROM friendships
             WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)",
            [$userId1, $userId2, $userId2, $userId1]
        )->fetch();

        return $row && (int) $row['sender_id'] === $userId1;
    }
}
