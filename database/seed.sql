-- ═══════════════════════════════════════════════════
-- GameVault — Jeu de données de test
-- seed.sql
-- ═══════════════════════════════════════════════════

USE gamevault;

-- 1. Nettoyage (Ordre inverse des dépendances)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE collection_stores;
TRUNCATE TABLE user_stores;
TRUNCATE TABLE stores;
TRUNCATE TABLE votes;
TRUNCATE TABLE messages;
TRUNCATE TABLE invitations;
TRUNCATE TABLE sessions;
TRUNCATE TABLE game_platforms;
TRUNCATE TABLE game_tags;
TRUNCATE TABLE collections;
TRUNCATE TABLE games;
TRUNCATE TABLE platforms;
TRUNCATE TABLE tags;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

-- 2. Insertion des Utilisateurs (mots de passe uniques, hachés via Bcrypt)
-- AlexGamer: AlexGamer42#  |  SarahStream: SarahStream99@  |  AdminVault: GameVault2026!
INSERT INTO users (username, email, password_hash, role) VALUES
('AlexGamer', 'alex@example.com', '$2y$10$uImzfNSmgg1BX2aQPCDhUu50gd3MS3N2i2CeKTn/X60oaBBB55ZF6', 'user'),
('SarahStream', 'sarah@example.com', '$2y$10$jwOwzcnj3X2lpvbR38LqwuaHmJUV1hxOStqhDgT7o6XAXCSKmn0Pm', 'user'),
('AdminVault', 'admin@gamevault.com', '$2y$10$SQHftxz8x.UJQgVDEh8.JOjisR2QzzAYXx6mff3sJDBelB0fZ4nuu', 'admin');

-- 3. Insertion des Stores (bibliotheques digitales)
INSERT INTO stores (name, icon) VALUES
('Steam', 'steam'),
('Epic Games Store', 'epic'),
('GOG', 'gog'),
('PlayStation Store', 'playstation'),
('Xbox / Microsoft Store', 'xbox'),
('Nintendo eShop', 'nintendo'),
('Ubisoft Connect', 'ubisoft'),
('EA App', 'ea'),
('Autre', 'other');

-- 4. Insertion des Tags
INSERT INTO tags (name) VALUES 
('RPG'), ('Action'), ('FPS'), ('Aventure'), ('Indie'), ('Strategie'), ('Sport');

-- 4. Insertion des Plateformes
INSERT INTO platforms (name) VALUES 
('Steam'), ('PlayStation 5'), ('Xbox Series X'), ('Nintendo Switch'), ('Epic Games Store');

-- 5. Insertion des Jeux
INSERT INTO games (title, description, release_date, rating, added_by) VALUES
('Elden Ring', 'Un jeu de rôle d action épique se déroulant dans l Entre-terre.', '2022-02-25', 9.5, 3),
('The Witcher 3', 'Traquez le fils de la prophétie dans un monde ouvert immense.', '2015-05-19', 9.8, 3),
('Valorant', 'FPS tactique basé sur des personnages en 5v5.', '2020-06-02', 8.0, 3),
('Hades', 'Un roguelike d action nerveux dans les enfers grecs.', '2020-09-17', 9.2, 3);

-- 6. Liaisons Jeux <-> Tags
INSERT INTO game_tags (game_id, tag_id) VALUES 
(1, 1), (1, 2), -- Elden Ring: RPG, Action
(2, 1), (2, 7), -- The Witcher: RPG, Aventure
(3, 3), (3, 2), -- Valorant: FPS, Action
(4, 5), (4, 2); -- Hades: Indie, Action

-- 7. Liaisons Jeux <-> Plateformes
INSERT INTO game_platforms (game_id, platform_id) VALUES 
(1, 1), (1, 2), (1, 3),
(2, 1), (2, 2), (2, 3),
(3, 1),
(4, 1), (4, 4);

-- 8. Collections Utilisateurs
INSERT INTO collections (user_id, game_id, personal_rating, notes) VALUES
(1, 1, 10, 'Mon jeu préféré de tous les temps.'),
(1, 4, 8, 'Très bon pour des petites sessions.'),
(2, 2, 9, 'Histoire incroyable.'),
(2, 3, 7, 'Sympa mais toxique parfois.');

-- 9. Sessions Gaming
INSERT INTO sessions (title, description, scheduled_at, max_players, status, organizer_id, selected_game_id) VALUES
('Soirée Elden Ring COOP', 'On va essayer de battre Malenia ensemble.', '2026-03-20 21:00:00', 3, 'planned', 1, 1),
('Scrim Valorant', 'Échauffement avant tournoi.', '2026-03-21 20:30:00', 5, 'planned', 2, 3);

-- 10. Invitations
INSERT INTO invitations (session_id, user_id, status) VALUES
(1, 2, 'accepted'),
(2, 1, 'pending');

-- 11. Messages
INSERT INTO messages (content, sender_id, session_id) VALUES
('Salut ! Prêt pour demain soir ?', 1, 1),
('Carrément, je prépare mon buildup.', 2, 1);
