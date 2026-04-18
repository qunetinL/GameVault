<?php

namespace App\Models;

use App\Core\Model;

class Game extends Model
{
    public function findAll($limit = null, $offset = 0)
    {
        $sql = "SELECT g.*, GROUP_CONCAT(t.name) as tags, u.username as added_by_name
             FROM games g 
             LEFT JOIN game_tags gt ON g.id = gt.game_id 
             LEFT JOIN tags t ON gt.tag_id = t.id 
             LEFT JOIN users u ON g.added_by = u.id
             GROUP BY g.id 
             ORDER BY g.created_at DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
        }

        return $this->query($sql)->fetchAll();
    }

    public function find($id)
    {
        return $this->query(
            "SELECT g.*, GROUP_CONCAT(t.name) as tags, GROUP_CONCAT(DISTINCT p.name) as platforms
             FROM games g 
             LEFT JOIN game_tags gt ON g.id = gt.game_id 
             LEFT JOIN tags t ON gt.tag_id = t.id 
             LEFT JOIN game_platforms gp ON g.id = gp.game_id
             LEFT JOIN platforms p ON gp.platform_id = p.id
             WHERE g.id = ?
             GROUP BY g.id",
            [$id]
        )->fetch();
    }

    public function search(string $query)
    {
        $q = "%$query%";
        return $this->query(
            "SELECT g.*, GROUP_CONCAT(t.name) as tags 
             FROM games g 
             LEFT JOIN game_tags gt ON g.id = gt.game_id 
             LEFT JOIN tags t ON gt.tag_id = t.id 
             WHERE g.title LIKE ? OR g.description LIKE ? 
             GROUP BY g.id 
             ORDER BY g.title ASC",
            [$q, $q]
        )->fetchAll();
    }

    public function getByUserCollection($userId)
    {
        return $this->query(
            "SELECT g.*, c.notes, c.personal_rating, c.added_at as collection_added_at,
                    GROUP_CONCAT(DISTINCT t.name) as tags,
                    GROUP_CONCAT(DISTINCT p.name) as platforms
             FROM games g 
             JOIN collections c ON g.id = c.game_id 
             LEFT JOIN game_tags gt ON g.id = gt.game_id
             LEFT JOIN tags t ON gt.tag_id = t.id
             LEFT JOIN game_platforms gp ON g.id = gp.game_id
             LEFT JOIN platforms p ON gp.platform_id = p.id
             WHERE c.user_id = ?
             GROUP BY g.id, c.id
             ORDER BY c.added_at DESC",
            [$userId]
        )->fetchAll();
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO games (title, description, cover_image, release_date, rating, added_by) 
                VALUES (:title, :description, :cover_image, :release_date, :rating, :added_by)";

        $this->query($sql, [
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':cover_image' => $data['cover_image'] ?? null,
            ':release_date' => $data['release_date'] ?? null,
            ':rating' => $data['rating'] ?? 0,
            ':added_by' => $data['added_by']
        ]);

        return self::$db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $sql = "UPDATE games SET 
                title = :title, 
                description = :description, 
                cover_image = :cover_image, 
                release_date = :release_date, 
                rating = :rating 
                WHERE id = :id";

        return $this->query($sql, [
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':cover_image' => $data['cover_image'] ?? null,
            ':release_date' => $data['release_date'] ?? null,
            ':rating' => $data['rating'] ?? 0
        ]);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM games WHERE id = ?", [$id]);
    }

    public function isInCollection($userId, $gameId)
    {
        $res = $this->query("SELECT 1 FROM collections WHERE user_id = ? AND game_id = ?", [$userId, $gameId])->fetch();
        return (bool) $res;
    }

    public function addToCollection($userId, $gameId)
    {
        return $this->query("INSERT IGNORE INTO collections (user_id, game_id) VALUES (?, ?)", [$userId, $gameId]);
    }

    public function removeFromCollection($userId, $gameId)
    {
        return $this->query("DELETE FROM collections WHERE user_id = ? AND game_id = ?", [$userId, $gameId]);
    }

    public function linkTag(int $gameId, string $tagName): void
    {
        // Créer le tag s'il n'existe pas
        $this->query("INSERT IGNORE INTO tags (name) VALUES (?)", [$tagName]);
        $tag = $this->query("SELECT id FROM tags WHERE name = ?", [$tagName])->fetch();
        if ($tag) {
            $this->query("INSERT IGNORE INTO game_tags (game_id, tag_id) VALUES (?, ?)", [$gameId, $tag['id']]);
        }
    }

    public function linkPlatform(int $gameId, string $platformName): void
    {
        // Créer la plateforme si elle n'existe pas
        $this->query("INSERT IGNORE INTO platforms (name) VALUES (?)", [$platformName]);
        $platform = $this->query("SELECT id FROM platforms WHERE name = ?", [$platformName])->fetch();
        if ($platform) {
            $this->query("INSERT IGNORE INTO game_platforms (game_id, platform_id) VALUES (?, ?)", [$gameId, $platform['id']]);
        }
    }
}
