<?php

namespace App\Models;

use App\Core\Model;

class Game extends Model
{
    public function getAll()
    {
        return $this->query("SELECT * FROM games ORDER BY title ASC")->fetchAll();
    }

    public function find($id)
    {
        return $this->query("SELECT * FROM games WHERE id = ?", [$id])->fetch();
    }

    public function getByUserCollection($userId)
    {
        return $this->query(
            "SELECT g.*, c.notes, c.personal_rating 
             FROM games g 
             JOIN collections c ON g.id = c.game_id 
             WHERE c.user_id = ?",
            [$userId]
        )->fetchAll();
    }
}
