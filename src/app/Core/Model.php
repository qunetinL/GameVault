<?php

namespace App\Core;

use PDO;
use PDOException;

abstract class Model
{
    protected static $db = null;

    public function __construct()
    {
        if (self::$db === null) {
            $this->connect();
        }
    }

    private function getEnv($key, $default = null)
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }

    private function connect()
    {
        $host = $this->getEnv('DB_HOST', 'localhost');
        $port = $this->getEnv('DB_PORT', '3306');
        $dbname = $this->getEnv('DB_NAME', 'gamevault');
        $user = $this->getEnv('DB_USER', 'root');
        $pass = $this->getEnv('DB_PASS', '');

        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
            self::$db = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            throw new \Exception("Erreur de connexion à la base de données.");
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
