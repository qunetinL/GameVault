<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function findByEmail($email)
    {
        return $this->query("SELECT * FROM users WHERE email = ?", [$email])->fetch();
    }

    public function findByUsername($username)
    {
        return $this->query("SELECT * FROM users WHERE username = ?", [$username])->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO users (username, email, password_hash, role, consent_at, email_token, created_at)
                VALUES (:username, :email, :password_hash, :role, :consent_at, :email_token, NOW())";

        $params = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role'] ?? 'user',
            'consent_at' => $data['consent_at'] ?? date('Y-m-d H:i:s'),
            'email_token' => $data['email_token'] ?? null
        ];

        return $this->query($sql, $params);
    }

    public function findByEmailToken(string $token)
    {
        return $this->query("SELECT * FROM users WHERE email_token = ?", [$token])->fetch();
    }

    public function verifyEmail(int $id): void
    {
        $this->query("UPDATE users SET email_verified_at = NOW(), email_token = NULL WHERE id = ?", [$id]);
    }

    public function updateEmailToken(int $id, string $token): void
    {
        $this->query("UPDATE users SET email_token = ? WHERE id = ?", [$token, $id]);
    }

    public function getStats($userId)
    {
        $stats = [];

        // Games in personal collection
        $stats['collection_count'] = $this->query("SELECT COUNT(*) FROM collections WHERE user_id = ?", [$userId])->fetchColumn();

        // Games added to the global catalogue by this user
        $stats['contributions_count'] = $this->query("SELECT COUNT(*) FROM games WHERE added_by = ?", [$userId])->fetchColumn();

        // Total sessions organized
        $stats['sessions_count'] = $this->query("SELECT COUNT(*) FROM sessions WHERE organizer_id = ?", [$userId])->fetchColumn();

        // Total messages sent
        $stats['messages_count'] = $this->query("SELECT COUNT(*) FROM messages WHERE sender_id = ?", [$userId])->fetchColumn();

        // Total invitations received
        $stats['invitations_count'] = $this->query("SELECT COUNT(*) FROM invitations WHERE user_id = ?", [$userId])->fetchColumn();

        return $stats;
    }
    public function findAll()
    {
        return $this->query("SELECT id, username, email, role, status, created_at FROM users ORDER BY created_at DESC")->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        return $this->query("UPDATE users SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function updateRole($id, $role)
    {
        return $this->query("UPDATE users SET role = ? WHERE id = ?", [$role, $id]);
    }

    public function getGlobalStats()
    {
        return [
            'total_users' => $this->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_games' => $this->query("SELECT COUNT(*) FROM games")->fetchColumn(),
            'total_sessions' => $this->query("SELECT COUNT(*) FROM sessions")->fetchColumn(),
            'pending_games' => $this->query("SELECT COUNT(*) FROM games WHERE status = 'pending'")->fetchColumn(),
        ];
    }

    public function updateProfile($id, $data)
    {
        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        return $this->query($sql, [
            'username' => $data['username'],
            'email' => $data['email'],
            'id' => $id
        ]);
    }

    public function deleteAccount($id)
    {
        return $this->query("DELETE FROM users WHERE id = ?", [$id]);
    }

    public function exportData($id)
    {
        $user = $this->query("SELECT id, username, email, role, status, created_at, consent_at FROM users WHERE id = ?", [$id])->fetch();
        $collections = $this->query("SELECT * FROM collections WHERE user_id = ?", [$id])->fetchAll();
        $messages = $this->query("SELECT * FROM messages WHERE sender_id = ?", [$id])->fetchAll();
        $sessions = $this->query("SELECT * FROM sessions WHERE organizer_id = ?", [$id])->fetchAll();
        $votes = $this->query("SELECT * FROM votes WHERE user_id = ?", [$id])->fetchAll();

        return [
            'user' => $user,
            'collections' => $collections,
            'messages' => $messages,
            'sessions' => $sessions,
            'votes' => $votes,
        ];
    }

    public function findById($id)
    {
        return $this->query("SELECT * FROM users WHERE id = ?", [$id])->fetch();
    }
}
