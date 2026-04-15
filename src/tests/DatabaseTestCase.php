<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Classe de base pour les tests nécessitant une connexion à la base de données.
 * Crée le schéma gamevault_test et nettoie les tables entre chaque test.
 */
abstract class DatabaseTestCase extends TestCase
{
    protected static PDO $pdo;
    protected static bool $schemaLoaded = false;

    public static function setUpBeforeClass(): void
    {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $dbname = $_ENV['DB_NAME'] ?? 'gamevault_test';
        $user = $_ENV['DB_USER'] ?? 'gamer';
        $pass = $_ENV['DB_PASS'] ?? 'password';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        self::$pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        if (!self::$schemaLoaded) {
            self::loadSchema();
            self::$schemaLoaded = true;
        }
    }

    private static function loadSchema(): void
    {
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        $tables = [
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                avatar VARCHAR(255) DEFAULT NULL,
                role ENUM('user', 'admin') DEFAULT 'user',
                status ENUM('active', 'banned') DEFAULT 'active',
                email_verified_at TIMESTAMP NULL DEFAULT NULL,
                email_token VARCHAR(64) DEFAULT NULL,
                reset_token VARCHAR(64) DEFAULT NULL,
                reset_token_expires_at TIMESTAMP NULL DEFAULT NULL,
                consent_at TIMESTAMP NULL DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email_token (email_token),
                INDEX idx_reset_token (reset_token)
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) UNIQUE NOT NULL
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS platforms (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) UNIQUE NOT NULL
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS games (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150) NOT NULL,
                description TEXT DEFAULT NULL,
                cover_image VARCHAR(255) DEFAULT NULL,
                release_date DATE DEFAULT NULL,
                rating DECIMAL(3,1) DEFAULT 0.0,
                added_by INT NOT NULL,
                status ENUM('approved', 'pending') DEFAULT 'approved',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_games_added_by FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS collections (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                game_id INT NOT NULL,
                personal_rating TINYINT DEFAULT NULL,
                notes TEXT DEFAULT NULL,
                added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uk_user_game (user_id, game_id),
                CONSTRAINT fk_collections_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                CONSTRAINT fk_collections_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS game_tags (
                game_id INT NOT NULL,
                tag_id INT NOT NULL,
                PRIMARY KEY (game_id, tag_id),
                CONSTRAINT fk_game_tags_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
                CONSTRAINT fk_game_tags_tag FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS game_platforms (
                game_id INT NOT NULL,
                platform_id INT NOT NULL,
                PRIMARY KEY (game_id, platform_id),
                CONSTRAINT fk_game_platforms_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
                CONSTRAINT fk_game_platforms_platform FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS sessions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150) NOT NULL,
                description TEXT DEFAULT NULL,
                scheduled_at DATETIME NOT NULL,
                max_players INT DEFAULT 10,
                status ENUM('planned', 'in_progress', 'completed') DEFAULT 'planned',
                organizer_id INT NOT NULL,
                selected_game_id INT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_sessions_organizer FOREIGN KEY (organizer_id) REFERENCES users(id) ON DELETE CASCADE,
                CONSTRAINT fk_sessions_game FOREIGN KEY (selected_game_id) REFERENCES games(id) ON DELETE SET NULL
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS invitations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                session_id INT NOT NULL,
                user_id INT NOT NULL,
                status ENUM('pending', 'accepted', 'refused') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uk_session_user (session_id, user_id),
                CONSTRAINT fk_invitations_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
                CONSTRAINT fk_invitations_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                content TEXT NOT NULL,
                sender_id INT NOT NULL,
                session_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_read TINYINT(1) DEFAULT 0,
                CONSTRAINT fk_messages_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
                CONSTRAINT fk_messages_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            "CREATE TABLE IF NOT EXISTS votes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                session_id INT NOT NULL,
                user_id INT NOT NULL,
                game_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uk_vote_session_user (session_id, user_id),
                CONSTRAINT fk_votes_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
                CONSTRAINT fk_votes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                CONSTRAINT fk_votes_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
            ) ENGINE=InnoDB",
        ];

        foreach ($tables as $sql) {
            self::$pdo->exec($sql);
        }

        self::$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->truncateTables();

        // Réinitialiser la connexion statique du Model pour utiliser la DB de test
        $reflection = new \ReflectionClass(\App\Core\Model::class);
        $dbProp = $reflection->getProperty('db');
        $dbProp->setAccessible(true);
        $dbProp->setValue(null, null);
    }

    private function truncateTables(): void
    {
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        $tables = ['votes', 'messages', 'invitations', 'sessions', 'collections', 'game_platforms', 'game_tags', 'games', 'platforms', 'tags', 'users'];

        foreach ($tables as $table) {
            self::$pdo->exec("TRUNCATE TABLE `$table`");
        }

        self::$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    protected function createTestUser(array $overrides = []): int
    {
        $defaults = [
            'username' => 'testuser_' . uniqid(),
            'email' => 'test_' . uniqid() . '@example.com',
            'password_hash' => password_hash('Password123!', PASSWORD_BCRYPT),
            'role' => 'user',
            'status' => 'active',
            'consent_at' => date('Y-m-d H:i:s'),
        ];

        $data = array_merge($defaults, $overrides);

        self::$pdo->prepare(
            "INSERT INTO users (username, email, password_hash, role, status, consent_at, created_at)
             VALUES (:username, :email, :password_hash, :role, :status, :consent_at, NOW())"
        )->execute($data);

        return (int) self::$pdo->lastInsertId();
    }

    protected function createTestGame(int $addedBy, array $overrides = []): int
    {
        $defaults = [
            'title' => 'Test Game ' . uniqid(),
            'description' => 'A test game description',
            'release_date' => '2024-01-15',
            'rating' => 8.5,
            'added_by' => $addedBy,
        ];

        $data = array_merge($defaults, $overrides);

        self::$pdo->prepare(
            "INSERT INTO games (title, description, release_date, rating, added_by)
             VALUES (:title, :description, :release_date, :rating, :added_by)"
        )->execute($data);

        return (int) self::$pdo->lastInsertId();
    }
}
