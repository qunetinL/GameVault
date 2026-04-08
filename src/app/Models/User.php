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
        $sql = "INSERT INTO users (username, email, password_hash, role, created_at) 
                VALUES (:username, :email, :password_hash, :role, NOW())";

        $params = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role'] ?? 'user'
        ];

        return $this->query($sql, $params);
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
}
