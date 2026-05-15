-- ═══════════════════════════════════════════════════
-- GameVault — Jeu de données de démonstration
-- seed.sql
-- ═══════════════════════════════════════════════════

SET NAMES utf8mb4;
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

-- ─────────────────────────────────────────────────
-- 2. Utilisateurs
-- AlexGamer: AlexGamer42#  |  SarahStream: SarahStream99@  |  AdminVault: GameVault2026!
-- ─────────────────────────────────────────────────
INSERT INTO users (username, email, password_hash, role) VALUES
('AlexGamer', 'alex@example.com', '$2y$10$uImzfNSmgg1BX2aQPCDhUu50gd3MS3N2i2CeKTn/X60oaBBB55ZF6', 'user'),
('SarahStream', 'sarah@example.com', '$2y$10$jwOwzcnj3X2lpvbR38LqwuaHmJUV1hxOStqhDgT7o6XAXCSKmn0Pm', 'user'),
('AdminVault', 'admin@gamevault.com', '$2y$10$SQHftxz8x.UJQgVDEh8.JOjisR2QzzAYXx6mff3sJDBelB0fZ4nuu', 'admin');

-- ─────────────────────────────────────────────────
-- 3. Stores (bibliothèques digitales)
-- ─────────────────────────────────────────────────
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

-- ─────────────────────────────────────────────────
-- 4. User Stores (comptes des joueurs)
-- Alex : Steam + Epic | Sarah : Steam + PS Store | Admin : Steam + Epic + GOG
-- ─────────────────────────────────────────────────
INSERT INTO user_stores (user_id, store_id) VALUES
(1, 1), (1, 2),       -- Alex: Steam, Epic
(2, 1), (2, 4),       -- Sarah: Steam, PS Store
(3, 1), (3, 2), (3, 3); -- Admin: Steam, Epic, GOG

-- ─────────────────────────────────────────────────
-- 5. Tags
-- ─────────────────────────────────────────────────
INSERT INTO tags (name) VALUES
('RPG'), ('Action'), ('FPS'), ('Aventure'), ('Indie'), ('Strategie'), ('Sport');

-- ─────────────────────────────────────────────────
-- 6. Plateformes matérielles
-- ─────────────────────────────────────────────────
INSERT INTO platforms (name) VALUES
('PC'), ('PlayStation 5'), ('Xbox Series X'), ('Nintendo Switch');

-- ─────────────────────────────────────────────────
-- 7. Jeux
-- ─────────────────────────────────────────────────
INSERT INTO games (title, description, release_date, rating, added_by) VALUES
('Elden Ring', 'Un jeu de rôle d''action épique se déroulant dans l''Entre-terre, créé par FromSoftware et Hidetaka Miyazaki en collaboration avec George R.R. Martin.', '2022-02-25', 9.5, 3),
('The Witcher 3', 'Traquez Ciri, l''enfant de la prophétie, dans un monde ouvert immense rempli de monstres, de choix moraux et d''histoires captivantes.', '2015-05-19', 9.8, 3),
('Valorant', 'FPS tactique 5v5 avec des agents dotés de capacités uniques. Mélange stratégique entre Counter-Strike et Overwatch.', '2020-06-02', 8.0, 3),
('Hades', 'Un roguelike d''action nerveux où vous incarnez Zagreus, fils d''Hadès, tentant de s''échapper des Enfers grecs.', '2020-09-17', 9.2, 3),
('Cyberpunk 2077', 'Un RPG en monde ouvert dans une mégapole dystopique. Incarnez V et naviguez dans les rues de Night City.', '2020-12-10', 8.5, 1),
('It Takes Two', 'Aventure coopérative primée où Cody et May doivent traverser des mondes fantastiques pour sauver leur relation.', '2021-03-26', 9.0, 2);

-- ─────────────────────────────────────────────────
-- 8. Liaisons Jeux <-> Tags
-- ─────────────────────────────────────────────────
INSERT INTO game_tags (game_id, tag_id) VALUES
(1, 1), (1, 2),       -- Elden Ring: RPG, Action
(2, 1), (2, 4),       -- Witcher 3: RPG, Aventure
(3, 3), (3, 2),       -- Valorant: FPS, Action
(4, 5), (4, 2),       -- Hades: Indie, Action
(5, 1), (5, 2),       -- Cyberpunk: RPG, Action
(6, 4), (6, 5);       -- It Takes Two: Aventure, Indie

-- ─────────────────────────────────────────────────
-- 9. Liaisons Jeux <-> Plateformes
-- ─────────────────────────────────────────────────
INSERT INTO game_platforms (game_id, platform_id) VALUES
(1, 1), (1, 2), (1, 3),  -- Elden Ring: PC, PS5, Xbox
(2, 1), (2, 2), (2, 4),  -- Witcher 3: PC, PS5, Switch
(3, 1),                    -- Valorant: PC
(4, 1), (4, 4),           -- Hades: PC, Switch
(5, 1), (5, 2), (5, 3),  -- Cyberpunk: PC, PS5, Xbox
(6, 1), (6, 2), (6, 3);  -- It Takes Two: PC, PS5, Xbox

-- ─────────────────────────────────────────────────
-- 10. Collections Utilisateurs
-- ─────────────────────────────────────────────────
INSERT INTO collections (user_id, game_id, personal_rating, notes) VALUES
(1, 1, 10, 'Mon jeu préféré de tous les temps. 200h+ de jeu.'),
(1, 4, 8, 'Très bon pour des petites sessions. Le gameplay est addictif.'),
(1, 5, 9, 'Night City est incroyable après les patchs.'),
(1, 3, 7, 'Je monte en rang petit à petit.'),
(2, 2, 9, 'Histoire incroyable. Meilleur RPG jamais fait.'),
(2, 3, 7, 'Sympa mais toxique parfois en ranked.'),
(2, 6, 10, 'Meilleur jeu coop de tous les temps !'),
(2, 1, 8, 'Dur mais très satisfaisant.'),
(3, 1, 9, 'Un chef-d''oeuvre moderne.'),
(3, 2, 10, 'Le GOAT des RPGs.'),
(3, 5, 8, 'Enfin jouable et magnifique.'),
(3, 6, 9, 'Parfait pour jouer en duo.');

-- ─────────────────────────────────────────────────
-- 11. Collection Stores (sur quel store chaque joueur possède le jeu)
-- collections IDs: 1=Alex/Elden, 2=Alex/Hades, 3=Alex/Cyber, 4=Alex/Valo,
--                  5=Sarah/Witcher, 6=Sarah/Valo, 7=Sarah/ItTakes, 8=Sarah/Elden,
--                  9=Admin/Elden, 10=Admin/Witcher, 11=Admin/Cyber, 12=Admin/ItTakes
-- ─────────────────────────────────────────────────
INSERT INTO collection_stores (collection_id, store_id) VALUES
(1, 1),        -- Alex a Elden Ring sur Steam
(2, 1),        -- Alex a Hades sur Steam
(3, 2),        -- Alex a Cyberpunk sur Epic
(4, 1),        -- Alex a Valorant sur Steam (Riot mais via Steam)
(5, 1),        -- Sarah a Witcher 3 sur Steam
(6, 1),        -- Sarah a Valorant sur Steam
(7, 4),        -- Sarah a It Takes Two sur PS Store
(8, 4),        -- Sarah a Elden Ring sur PS Store
(9, 1), (9, 3),  -- Admin a Elden Ring sur Steam et GOG
(10, 1),       -- Admin a Witcher 3 sur Steam
(11, 1), (11, 2), -- Admin a Cyberpunk sur Steam et Epic
(12, 1);       -- Admin a It Takes Two sur Steam

-- ─────────────────────────────────────────────────
-- 12. Sessions Gaming
-- ─────────────────────────────────────────────────
INSERT INTO sessions (title, description, scheduled_at, max_players, status, organizer_id, selected_game_id) VALUES
('Soirée Elden Ring COOP', 'On va essayer de battre Malenia ensemble.', '2026-06-20 21:00:00', 3, 'planned', 1, 1),
('Scrim Valorant', 'Échauffement avant tournoi.', '2026-06-21 20:30:00', 5, 'planned', 2, 3),
('Soirée découverte — On joue à quoi ?', 'Session chill, on vote et on joue ensemble ! Proposez vos jeux.', '2026-06-22 20:00:00', 5, 'planned', 3, NULL);

-- ─────────────────────────────────────────────────
-- 13. Invitations
-- ─────────────────────────────────────────────────
INSERT INTO invitations (session_id, user_id, status) VALUES
(1, 2, 'accepted'),           -- Sarah invitée à Soirée Elden Ring
(1, 3, 'accepted'),           -- Admin invité à Soirée Elden Ring
(2, 1, 'accepted'),           -- Alex invité au Scrim Valorant
(3, 1, 'accepted'),           -- Alex invité à Soirée découverte
(3, 2, 'accepted');           -- Sarah invitée à Soirée découverte

-- ─────────────────────────────────────────────────
-- 14. Votes (session 3 uniquement — pas de jeu sélectionné)
-- ─────────────────────────────────────────────────
INSERT INTO votes (session_id, user_id, game_id) VALUES
(3, 1, 5),   -- Alex vote Cyberpunk
(3, 2, 6),   -- Sarah vote It Takes Two
(3, 3, 1);   -- Admin vote Elden Ring

-- ─────────────────────────────────────────────────
-- 15. Messages
-- ─────────────────────────────────────────────────
INSERT INTO messages (content, sender_id, session_id, created_at) VALUES
-- Session 1 : Soirée Elden Ring
('Salut ! Prêt pour demain soir ?', 1, 1, '2026-06-19 18:30:00'),
('Carrément, je prépare mon build.', 2, 1, '2026-06-19 18:32:00'),
('Je serai là aussi, j''ai un build magie noire à tester.', 3, 1, '2026-06-19 18:45:00'),
('Parfait on sera 3 ! On commence par le boss ou on explore ?', 1, 1, '2026-06-19 18:47:00'),
('On rush Malenia direct, pas le choix.', 2, 1, '2026-06-19 18:50:00'),

-- Session 2 : Scrim Valorant
('T''es dispo pour un warm-up ce soir ?', 2, 2, '2026-06-20 17:00:00'),
('Oui, je lance Valorant dans 30 min.', 1, 2, '2026-06-20 17:05:00'),
('On prend Ascent ou Bind ?', 2, 2, '2026-06-20 17:10:00'),
('Ascent, c''est ma meilleure map.', 1, 2, '2026-06-20 17:12:00'),

-- Session 3 : Soirée découverte (votes visibles ici)
('Salut tout le monde ! Ce soir on joue ensemble, votez pour votre jeu !', 3, 3, '2026-06-21 19:00:00'),
('Moi je vote Cyberpunk, j''ai envie de revoir Night City.', 1, 3, '2026-06-21 19:05:00'),
('It Takes Two en coop ce serait trop bien !', 2, 3, '2026-06-21 19:08:00'),
('Elden Ring pour moi, toujours.', 3, 3, '2026-06-21 19:10:00'),
('On attend encore un peu pour voir si tout le monde a voté ?', 2, 3, '2026-06-21 19:15:00'),
('Oui, on lance dans 45 min !', 3, 3, '2026-06-21 19:17:00');
