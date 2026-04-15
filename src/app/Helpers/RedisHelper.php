<?php

namespace App\Helpers;

use Redis;
use RedisException;

class RedisHelper
{
    private static $instance = null;
    private $redis;

    private function __construct()
    {
        if (!class_exists('Redis')) {
            $this->redis = null;
            return;
        }

        try {
            $this->redis = new Redis();
            // In Docker, the host is 'redis' (the service name)
            $this->redis->connect(getenv('REDIS_HOST') ?: 'redis', 6379);
        } catch (RedisException $e) {
            $this->redis = null;
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * Increment a game's view count
     */
    public function incrementGameView($gameId)
    {
        if ($this->redis) {
            $this->redis->hIncrBy('game:views', $gameId, 1);
        }
    }

    /**
     * Get view counts for all games
     */
    public function getGameViews()
    {
        if ($this->redis) {
            return $this->redis->hGetAll('game:views');
        }
        return [];
    }

    /**
     * Log user activity
     */
    public function logActivity($userId, $action)
    {
        if ($this->redis) {
            $log = json_encode([
                'user_id' => $userId,
                'action' => $action,
                'timestamp' => time()
            ]);
            $this->redis->lPush("user:{$userId}:activity", $log);
            $this->redis->lTrim("user:{$userId}:activity", 0, 99); // Keep last 100
        }
    }

    /**
     * Get user activity history
     */
    public function getActivity($userId)
    {
        if ($this->redis) {
            $logs = $this->redis->lRange("user:{$userId}:activity", 0, -1);
            return array_map(fn($l) => json_decode($l, true), $logs);
        }
        return [];
    }

    /**
     * Simple Cache Set
     */
    public function setCache($key, $data, $ttl = 3600)
    {
        if ($this->redis) {
            $this->redis->setex($key, $ttl, json_encode($data));
        }
    }

    /**
     * Simple Cache Get
     */
    public function getCache($key)
    {
        if ($this->redis) {
            $data = $this->redis->get($key);
            return $data ? json_decode($data, true) : null;
        }
        return null;
    }

    /**
     * Simple Cache Delete
     */
    public function deleteCache($key)
    {
        if ($this->redis) {
            $this->redis->del($key);
        }
    }

    /**
     * Delete all Redis data associated with a user (RGPD - droit à l'effacement)
     */
    public function deleteUserData($userId)
    {
        if ($this->redis) {
            $this->redis->del("user:{$userId}:activity");
            // Delete any other user-specific keys matching the pattern
            $keys = $this->redis->keys("user:{$userId}:*");
            if (!empty($keys)) {
                $this->redis->del(...$keys);
            }
        }
    }
}
