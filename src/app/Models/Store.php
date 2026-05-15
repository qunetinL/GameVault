<?php

namespace App\Models;

use App\Core\Model;

class Store extends Model
{
    public function findAll()
    {
        return $this->query("SELECT * FROM stores ORDER BY name ASC")->fetchAll();
    }

    public function getUserStores(int $userId): array
    {
        return $this->query(
            "SELECT s.* FROM stores s
             JOIN user_stores us ON s.id = us.store_id
             WHERE us.user_id = ?
             ORDER BY s.name ASC",
            [$userId]
        )->fetchAll();
    }

    public function setUserStores(int $userId, array $storeIds): void
    {
        $this->query("DELETE FROM user_stores WHERE user_id = ?", [$userId]);
        foreach ($storeIds as $storeId) {
            $this->query(
                "INSERT IGNORE INTO user_stores (user_id, store_id) VALUES (?, ?)",
                [$userId, (int) $storeId]
            );
        }
    }

    public function getCollectionStores(int $collectionId): array
    {
        return $this->query(
            "SELECT s.* FROM stores s
             JOIN collection_stores cs ON s.id = cs.store_id
             WHERE cs.collection_id = ?
             ORDER BY s.name ASC",
            [$collectionId]
        )->fetchAll();
    }

    public function setCollectionStores(int $collectionId, array $storeIds): void
    {
        $this->query("DELETE FROM collection_stores WHERE collection_id = ?", [$collectionId]);
        foreach ($storeIds as $storeId) {
            $this->query(
                "INSERT IGNORE INTO collection_stores (collection_id, store_id) VALUES (?, ?)",
                [$collectionId, (int) $storeId]
            );
        }
    }

    public function getCollectionId(int $userId, int $gameId): ?int
    {
        $row = $this->query(
            "SELECT id FROM collections WHERE user_id = ? AND game_id = ?",
            [$userId, $gameId]
        )->fetch();
        return $row ? (int) $row['id'] : null;
    }

    /**
     * Pour un ensemble de jeux votés dans une session, retourne les owners et leurs stores.
     */
    public function getSessionGameOwners(int $sessionId): array
    {
        return $this->query(
            "SELECT DISTINCT g.id as game_id, g.title, u.id as user_id, u.username,
                    GROUP_CONCAT(DISTINCT s.name ORDER BY s.name SEPARATOR ', ') as stores
             FROM votes v
             JOIN games g ON v.game_id = g.id
             JOIN collections c ON c.game_id = g.id
             JOIN users u ON c.user_id = u.id
             LEFT JOIN collection_stores cs ON cs.collection_id = c.id
             LEFT JOIN stores s ON cs.store_id = s.id
             WHERE v.session_id = ?
             GROUP BY g.id, u.id
             ORDER BY g.title, u.username",
            [$sessionId]
        )->fetchAll();
    }
}
