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
}
