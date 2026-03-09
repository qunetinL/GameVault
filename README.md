# 🎮 GameVault

Plateforme communautaire de gestion de collections de jeux vidéo et d'organisation de sessions gaming entre joueurs. Projet réalisé dans le cadre du titre professionnel Développeur Web et Web Mobile (DWWM).

## Objectifs du Projet
- **Gérer sa collection de jeux** (Ajout, modification, suppression, notes, tags)
- **Organiser des sessions gaming** (Création d'événements, invitations, votes)
- **Communiquer en temps réel** (Chat entre joueurs)
- **Rechercher et filtrer** (AJAX, recherche sans rechargement)
- **Consulter des statistiques** (Dashboard interactif)

## Technologies Utilisées
- **Front-end** : HTML5, CSS3 (Vanilla), JavaScript (AJAX, Fetch)
- **Back-end** : PHP 8.2 (Architecture MVC maison)
- **Bases de données** : MySQL 8.x
- **Déploiement** : Docker, docker-compose, GitHub Actions

## Installation (Développement avec Docker)

1. Clonez ce dépôt :
   ```bash
   git clone https://github.com/votre-utilisateur/GameVault.git
   ```
2. Lancez les conteneurs :
   ```bash
   docker-compose up -d --build
   ```
3. L'application est disponible sur : `http://localhost:8080`
   PhpMyAdmin est sur : `http://localhost:8081`

> Note : Le projet utilise le pattern MVC et la racine web sert le dossier `src/public`.