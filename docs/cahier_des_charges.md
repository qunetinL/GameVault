# Cahier des Charges Fonctionnel - GameVault

## 1. Contexte et Problématique
Les joueurs de jeux vidéo ont souvent besoin de centraliser leur collection, répartie sur de multiples plateformes (Steam, consoles, etc.), et de s'organiser facilement avec leurs amis. **GameVault** répond à ce besoin en offrant une plateforme communautaire de gestion de collections de jeux vidéo et d'organisation de sessions gaming entre joueurs.
Ce projet s'inscrit dans la validation du titre professionnel Développeur Web et Web Mobile (DWWM).

## 2. Persona Utilisateur Type
**Alex, 25 ans, passionné de jeux vidéo ("Gamer")**
- **Profil :** Joue régulièrement sur plusieurs plateformes (PC, PS5, Switch). Il possède une grande collection de jeux.
- **Besoins :** 
  - Centraliser et gérer sa collection (ajouter, noter, taguer des jeux).
  - Organiser efficacement des sessions gaming avec ses amis (quand, à quoi jouer).
- **Frustrations :** Perte de temps à demander sur Discord "Qui a installé ce jeu ?" ou "À quoi on joue ce soir ?".

## 3. Liste des Fonctionnalités (Méthode MoSCoW)

### MUST HAVE (Fonctionnalités requises pour le projet DWWM)
1. **Système de profils (CP7, CP5)** : Inscription, connexion sécurisée (bcrypt), avatar, gestion des rôles (utilisateur/admin).
2. **Gestion de collection de jeux (CP3, CP7, CP6, CP5)** : Ajouter, modifier, supprimer des jeux avec images, notes, tags.
3. **Organisation de sessions gaming (CP7, CP5)** : Créer des événements, inviter des amis, système de votes sur le jeu à jouer.
4. **Recherche dynamique (CP4, CP6)** : Filtrage en temps réel par genre, plateforme, note (AJAX, fetch API, sans rechargement).
5. **Chat en temps réel (CP4, CP7)** : Messagerie entre joueurs pour organiser les sessions (via AJAX polling ou WebSocket).
6. **Dashboard statistiques (CP4, CP6)** : Graphiques de la collection (genres les plus joués, temps de jeu, nombre de jeux). Utilisation d'une base NoSQL.
7. **API REST (CP7)** : Endpoints JSON pour les opérations CRUD.
8. **Administration (CP7, CP5)** : Panel admin sécurisé pour modérer le contenu et gérer les utilisateurs.

### SHOULD HAVE (Fonctionnalités importantes complémentaires)
- Système de notifications en temps réel (Toasts JS).
- Importation d'images sécurisée avec vérification des types MIME.
- Thème sombre / clair (Dark/Light mode).

### COULD HAVE (Fonctionnalités bonus)
- Récupération automatique des données des jeux via une API externe (ex: IGDB).

### WON'T HAVE (Hors périmètre pour cette version)
- Monétisation ou boutique d'achat de jeux.
- Application mobile native (le site sera un design responsive "mobile-first").

## 4. Contraintes Techniques
- **Maquettage :** Figma (Wireframes et maquettes haute fidélité).
- **Front-end :** HTML5 sémantique, CSS3 (avec Bootstrap 5 ou Tailwind CSS), JavaScript Vanilla (fetch, manipulation du DOM, asynchrone).
- **Back-end :** PHP 8+ avec une architecture MVC "Maison".
- **Bases de Données :** 
  - **Relationnelle :** MySQL 8 / MariaDB (Jeux, Utilisateurs, Sessions).
  - **NoSQL :** MongoDB ou Redis (Stockage des statistiques, logs d'activité).
- **Déploiement et CI/CD :** Conteneurisation via Docker (docker-compose). Pipeline CI/CD avec GitHub Actions. Serveur Nginx.
- **Sécurité et Qualité :** OWASP Top 10 (bcrypt, tokens CSRF, PDO/Requêtes préparées, protection XSS avec `htmlspecialchars()`), Architecture REST.
- **Versioning :** Git et GitHub.
