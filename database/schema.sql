-- ═══════════════════════════════════════════════════
-- GameVault — Script de création de la base de données
-- schema.sql
-- ═══════════════════════════════════════════════════

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS gamevault
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gamevault;

-- ─────────────────────────────────────────────────
-- TABLE : users
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
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
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : tags (genres/catégories de jeux)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : platforms (PC, PS5, Xbox, Switch…)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS platforms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : games
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    cover_image VARCHAR(255) DEFAULT NULL,
    release_date DATE DEFAULT NULL,
    rating DECIMAL(3,1) DEFAULT 0.0,
    added_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_games_added_by FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : collections (un utilisateur possède des jeux)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS collections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    personal_rating TINYINT DEFAULT NULL CHECK (personal_rating BETWEEN 0 AND 10),
    notes TEXT DEFAULT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_user_game (user_id, game_id),
    CONSTRAINT fk_collections_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_collections_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : game_tags (association many-to-many)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS game_tags (
    game_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (game_id, tag_id),
    CONSTRAINT fk_game_tags_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    CONSTRAINT fk_game_tags_tag FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : game_platforms (association many-to-many)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS game_platforms (
    game_id INT NOT NULL,
    platform_id INT NOT NULL,
    PRIMARY KEY (game_id, platform_id),
    CONSTRAINT fk_game_platforms_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    CONSTRAINT fk_game_platforms_platform FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : sessions (sessions gaming)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS sessions (
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
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : invitations
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS invitations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'refused') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_session_user (session_id, user_id),
    CONSTRAINT fk_invitations_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    CONSTRAINT fk_invitations_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : messages (chat de session)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    sender_id INT NOT NULL,
    session_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_messages_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_messages_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : votes (vote pour un jeu dans une session)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_vote_session_user (session_id, user_id),
    CONSTRAINT fk_votes_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    CONSTRAINT fk_votes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_votes_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : friendships (systeme d'amis)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS friendships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_friendship (sender_id, receiver_id),
    CONSTRAINT fk_friendships_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_friendships_receiver FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : stores (bibliotheques/magasins digitaux)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    icon VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : user_stores (stores lies au profil)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS user_stores (
    user_id INT NOT NULL,
    store_id INT NOT NULL,
    PRIMARY KEY (user_id, store_id),
    CONSTRAINT fk_user_stores_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_stores_store FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- TABLE : collection_stores (store par jeu possede)
-- ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS collection_stores (
    collection_id INT NOT NULL,
    store_id INT NOT NULL,
    PRIMARY KEY (collection_id, store_id),
    CONSTRAINT fk_collection_stores_collection FOREIGN KEY (collection_id) REFERENCES collections(id) ON DELETE CASCADE,
    CONSTRAINT fk_collection_stores_store FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────
-- Donnees initiales : stores
-- ─────────────────────────────────────────────────
INSERT IGNORE INTO stores (name, icon) VALUES
    ('Steam', 'steam'),
    ('Epic Games Store', 'epic'),
    ('GOG', 'gog'),
    ('PlayStation Store', 'playstation'),
    ('Xbox / Microsoft Store', 'xbox'),
    ('Nintendo eShop', 'nintendo'),
    ('Ubisoft Connect', 'ubisoft'),
    ('EA App', 'ea'),
    ('Autre', 'other');

SET FOREIGN_KEY_CHECKS = 1;
