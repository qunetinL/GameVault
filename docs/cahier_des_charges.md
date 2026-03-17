# Cahier des Charges — GameVault

**Titre professionnel visé** : Développeur Web et Web Mobile (DWWM)
**Document** : Cahier des charges fonctionnel et technique
**Version** : 1.0

---

## Table des Matières

1. [Contexte et Problématique](#1-contexte-et-problématique)
2. [Objectifs du Projet](#2-objectifs-du-projet)
3. [Personas Utilisateurs](#3-personas-utilisateurs)
4. [Périmètre Fonctionnel — Méthode MoSCoW](#4-périmètre-fonctionnel--méthode-moscow)
5. [Cas d'Utilisation UML](#5-cas-dutilisation-uml)
6. [Architecture Technique](#6-architecture-technique)
7. [Contraintes Techniques](#7-contraintes-techniques)
8. [Contraintes de Sécurité](#8-contraintes-de-sécurité)
9. [Exigences de Qualité](#9-exigences-de-qualité)

---

## 1. Contexte et Problématique

### 1.1 Contexte

La communauté des joueurs vidéo est vaste et en constante croissance. Les joueurs possèdent souvent des dizaines de jeux répartis sur plusieurs plateformes (PC, PlayStation, Xbox, Nintendo Switch), et organisent régulièrement des sessions gaming avec leurs amis. Cependant, il n'existe pas d'outil centralisé et accessible permettant à la fois :

- de gérer et visualiser sa propre collection de jeux,
- d'organiser des sessions gaming entre amis de façon collaborative,
- de communiquer facilement avec ses contacts dans un seul espace dédié.

### 1.2 Problématique

> **Comment permettre aux joueurs de gérer leur collection, d'organiser des sessions gaming collaboratives et de communiquer, dans une seule application web accessible et sécurisée ?**

Les solutions existantes sont soit trop complexes (Steam, Discord), soit partielles (simples listes). **GameVault** vise à offrir une expérience simple, centralisée et personnalisée.

### 1.3 Porteur du projet

Projet personnel développé dans le cadre du diplôme DWWM. Le candidat joue le rôle à la fois de développeur full-stack et de référent fonctionnel.

---

## 2. Objectifs du Projet

| Objectif | Description |
|----------|-------------|
| **OBJ-01** | Permettre à chaque utilisateur de constituer et gérer sa bibliothèque de jeux vidéo personnelle |
| **OBJ-02** | Faciliter l'organisation de sessions gaming en groupe avec vote collaboratif sur le jeu à jouer |
| **OBJ-03** | Offrir une recherche et un filtrage dynamiques (AJAX) sans rechargement de page |
| **OBJ-04** | Assurer la sécurité des données utilisateurs (authentification, prévention OWASP Top 10) |
| **OBJ-05** | Fournir une interface responsive, accessible et ergonomique (desktop + mobile) |
| **OBJ-06** | Proposer un panel d'administration pour la modération du contenu |
| **OBJ-07** | Exposer une API REST JSON pour toutes les opérations CRUD |
| **OBJ-08** | Déployer l'application via Docker avec pipeline CI/CD automatisé |

---

## 3. Personas Utilisateurs

### 3.1 Persona Principal — Le Joueur Régulier

**Nom** : Lucas, 24 ans
**Profil** : Étudiant, gamer passionné, possède 80+ jeux sur plusieurs plateformes
**Besoins** :
- Retrouver facilement un jeu dans sa collection
- Organiser des soirées gaming avec ses 5-6 amis
- Décider rapidement du jeu à jouer via un vote
- Recevoir des notifications quand une session est planifiée

**Frustrations actuelles** :
- Doit gérer des listes sur des applications différentes (feuilles de calcul, Discord, etc.)
- Difficile de savoir qui possède quel jeu avant de planifier une session multijoueur

---

### 3.2 Persona Secondaire — Le Casual Gamer

**Nom** : Chloé, 31 ans
**Profil** : Professionnelle, joue occasionnellement le week-end
**Besoins** :
- Interface simple et intuitive, sans courbe d'apprentissage
- Recevoir des invitations de session facilement
- Pouvoir consulter l'application sur mobile

---

### 3.3 Persona Administrateur

**Nom** : Compte administrateur de la plateforme
**Profil** : Modérateur du contenu communautaire
**Besoins** :
- Voir et gérer tous les comptes utilisateurs
- Modérer les jeux et contenus signalés
- Accéder aux statistiques globales de la plateforme

---

## 4. Périmètre Fonctionnel — Méthode MoSCoW

### 4.1 Must Have — Fonctionnalités indispensables

| ID | Fonctionnalité | Description |
|----|---------------|-------------|
| M-01 | **Inscription / Connexion** | Création de compte avec email, pseudo et mot de passe sécurisé (bcrypt) |
| M-02 | **Gestion de la collection** | Ajouter, modifier, supprimer des jeux (titre, image, plateforme, genre, note, description) |
| M-03 | **Recherche dynamique AJAX** | Filtrage en temps réel par titre, genre ou plateforme sans rechargement de page |
| M-04 | **Création de sessions gaming** | Créer un événement avec date, nombre max de participants et liste de jeux proposés |
| M-05 | **Système d'invitations** | Inviter des contacts à rejoindre une session, accepter ou refuser |
| M-06 | **Profil utilisateur** | Avatar, pseudo, statistiques personnelles (nombre de jeux, sessions jouées) |
| M-07 | **API REST JSON** | Endpoints CRUD pour les jeux, sessions et utilisateurs |
| M-08 | **Base de données relationnelle MySQL** | Schéma normalisé avec contraintes, clés étrangères, données de test |
| M-09 | **Déploiement Docker** | docker-compose.yml avec PHP, MySQL, Nginx, phpMyAdmin |
| M-10 | **Panel d'administration** | Gestion des utilisateurs (rôles, ban) et modération du contenu |

---

### 4.2 Should Have — Fonctionnalités importantes

| ID | Fonctionnalité | Description |
|----|---------------|-------------|
| S-01 | **Vote sur le jeu de la session** | Chaque participant peut voter pour le jeu à jouer parmi les propositions |
| S-02 | **Messagerie instantanée** | Chat entre joueurs via AJAX polling (toutes les 3 secondes) |
| S-03 | **Dashboard statistiques** | Graphiques personnels : genres les plus joués, nombre de sessions, temps de jeu estimé |
| S-04 | **Filtres avancés** | Filtrage combiné par genre, plateforme, note minimale |
| S-05 | **Notifications** | Compteur de messages non lus, invitations en attente |
| S-06 | **Dark mode / Light mode** | Bascule de thème persistée en localStorage |
| S-07 | **Stockage NoSQL** | Redis ou MongoDB pour les compteurs de vues et le cache des recherches fréquentes |

---

### 4.3 Could Have — Fonctionnalités souhaitées si le temps le permet

| ID | Fonctionnalité | Description |
|----|---------------|-------------|
| C-01 | **Calendrier visuel des sessions** | Vue calendrier mensuelle pour visualiser toutes les sessions planifiées |
| C-02 | **Tags personnalisés** | Étiquettes libres sur les jeux (ex: "coopératif", "compétitif", "solo") |
| C-03 | **Confirmation d'email** | Envoi d'un email lors de l'inscription pour valider le compte |
| C-04 | **Import depuis API externe** | Recherche et import de jeux depuis une API publique (IGDB, RAWG) |
| C-05 | **Lazy loading** | Chargement progressif des images de la collection |
| C-06 | **Indicateur "en train d'écrire"** | Affichage en temps réel dans le chat |

---

### 4.4 Won't Have — Hors périmètre (version 1.0)

| ID | Fonctionnalité | Justification |
|----|---------------|---------------|
| W-01 | Intégration plateformes gaming (Steam API, PSN) | Complexité et contraintes d'API tierces |
| W-02 | Système de paiement | Aucun modèle économique prévu pour cette version |
| W-03 | WebSockets temps réel | AJAX polling suffisant pour la démonstration DWWM |
| W-04 | Gestion de tournois | Trop complexe pour le périmètre du dossier projet |

---

## 5. Cas d'Utilisation UML

### 5.1 Acteurs du système

| Acteur | Description |
|--------|-------------|
| **Visiteur** | Utilisateur non connecté, accès limité à la page d'accueil et aux formulaires d'auth |
| **Utilisateur** | Joueur connecté disposant de toutes les fonctionnalités de la plateforme |
| **Administrateur** | Utilisateur avec rôle `admin`, accès au panel de gestion |

---

### 5.2 Diagrammes UML
Les schémas de conception sont disponibles dans les fichiers Mermaid (.mmd) suivants, situés dans le répertoire `docs/` :

- **Cas d'Utilisation** : [diagramme_cas_utilisation.mmd](file:///home/iamacat/Documents/GitHub/GameVault/docs/diagramme_cas_utilisation.mmd)
- **Modèle Conceptuel de Données (MCD)** : [mcd.mmd](file:///home/iamacat/Documents/GitHub/GameVault/docs/mcd.mmd)
- **Modèle Logique de Données (MLD)** : [mld.mmd](file:///home/iamacat/Documents/GitHub/GameVault/docs/mld.mmd)
- **Diagramme de Classes** : [diagramme_classes.mmd](file:///home/iamacat/Documents/GitHub/GameVault/docs/diagramme_classes.mmd)

---

### 5.3 Description des cas d'utilisation prioritaires

#### UC-01 : S'inscrire

| Champ | Contenu |
|-------|---------|
| **Acteur** | Visiteur |
| **Précondition** | L'utilisateur n'a pas de compte existant |
| **Scénario nominal** | 1. Le visiteur remplit le formulaire (pseudo, email, mot de passe) ; 2. Validation côté client (JS) ; 3. Soumission du formulaire ; 4. Validation côté serveur (unicité email/pseudo, force du mot de passe) ; 5. Hashage bcrypt du mot de passe ; 6. Insertion en BDD ; 7. Redirection vers le dashboard |
| **Scénarios alternatifs** | Email déjà utilisé → message d'erreur ; Mot de passe trop faible → indicateur visuel |
| **Postcondition** | Compte créé, session PHP démarrée |

---

#### UC-02 : Rechercher un jeu (AJAX)

| Champ | Contenu |
|-------|---------|
| **Acteur** | Utilisateur |
| **Précondition** | L'utilisateur est connecté et sur la page "Ma Collection" |
| **Scénario nominal** | 1. L'utilisateur saisit un terme dans la barre de recherche ; 2. Un événement `input` déclenche une requête `fetch()` vers `/api/games/search?q=...` ; 3. Le serveur retourne un tableau JSON de jeux correspondants ; 4. Le JavaScript re-rend les cartes de jeux sans rechargement |
| **Scénarios alternatifs** | Aucun résultat → affichage du message "Aucun jeu trouvé" ; Erreur réseau → message d'erreur toast |
| **Postcondition** | La liste de jeux affichée correspond au filtre saisi |

---

#### UC-03 : Créer une session gaming

| Champ | Contenu |
|-------|---------|
| **Acteur** | Utilisateur |
| **Précondition** | L'utilisateur est connecté |
| **Scénario nominal** | 1. L'utilisateur accède à la page "Sessions" ; 2. Il clique sur "Créer une session" ; 3. Il remplit le formulaire (titre, date, nb max participants, jeux proposés) ; 4. Validation côté client puis serveur ; 5. Insertion en BDD ; 6. Possibilité d'inviter des contacts |
| **Contraintes** | La date ne peut pas être dans le passé ; nb max participants ≥ 2 |
| **Postcondition** | Session créée, visible dans la liste, invitations envoyées |

---

## 6. Architecture Technique

### 6.1 Stack technologique

| Couche | Technologie | Justification |
|--------|-------------|---------------|
| **Maquettage** | Figma | Standard industrie, version gratuite suffisante |
| **Front-end** | HTML5 / CSS3 / JavaScript vanilla | Démontre la maîtrise des bases sans framework |
| **CSS** | Bootstrap 5 ou Tailwind CSS | Responsive design rapide et cohérent |
| **Back-end** | PHP 8.2 (architecture MVC maison) | Compétence centrale du diplôme DWWM |
| **BDD relationnelle** | MySQL 8 / MariaDB | SGBD relationnel standard, conforme au diplôme |
| **BDD NoSQL** | Redis ou MongoDB | Statistiques, cache et compteurs d'activité |
| **Versioning** | Git + GitHub | Historique, branches features, pull requests |
| **Conteneurisation** | Docker + docker-compose | Isolation de l'environnement, déploiement reproductible |
| **CI/CD** | GitHub Actions | Automatisation du build et du déploiement |
| **Serveur web** | Nginx | Proxy inverse, URL rewriting, performances |

---

### 6.2 Architecture applicative (MVC)

```
src/
├── public/             # Point d'entrée web
│   ├── index.php       # Front controller
│   ├── css/
│   ├── js/
│   └── uploads/        # Images jeux (stockage sécurisé)
├── app/
│   ├── Controllers/    # Logique de contrôle (routes → actions)
│   ├── Models/         # Accès données via PDO
│   ├── Views/          # Templates PHP
│   ├── Middleware/     # Auth, CSRF, rate-limiting
│   ├── Services/       # Logique métier (sessions, votes)
│   └── Helpers/        # Fonctions utilitaires
├── config/
│   ├── database.php
│   └── app.php
├── tests/
└── database/
    ├── schema.sql      # CREATE TABLE
    └── seed.sql        # Données de test
```

---

### 6.3 Endpoints API REST

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| `GET` | `/api/games` | Liste paginée des jeux |
| `GET` | `/api/games/:id` | Détail d'un jeu |
| `POST` | `/api/games` | Créer un jeu |
| `PUT` | `/api/games/:id` | Modifier un jeu |
| `DELETE` | `/api/games/:id` | Supprimer un jeu |
| `GET` | `/api/games/search` | Recherche dynamique (`?q=...`) |
| `GET` | `/api/sessions` | Liste des sessions |
| `POST` | `/api/sessions` | Créer une session |
| `POST` | `/api/sessions/:id/vote` | Voter pour un jeu |
| `POST` | `/api/messages` | Envoyer un message |
| `GET` | `/api/messages/:userId` | Récupérer une conversation |
| `GET` | `/api/stats` | Statistiques de l'utilisateur connecté |

**Codes HTTP utilisés** : `200 OK`, `201 Created`, `400 Bad Request`, `401 Unauthorized`, `404 Not Found`, `500 Internal Server Error`

---

## 7. Contraintes Techniques

### 7.1 Environnement de développement

| Outil | Version | Usage |
|-------|---------|-------|
| **PHP** | 8.2+ | Back-end, PDO, sessions |
| **MySQL** | 8.0+ | Base de données principale |
| **Docker** | 24+ | Conteneurisation (PHP-FPM, MySQL, Nginx, phpMyAdmin) |
| **Node.js** | 20+ | Outils de build front-end (npm, Sass, optionnel) |
| **Composer** | 2.x | Autoloading PSR-4, dépendances PHP |
| **VS Code** | dernière | IDE principal avec extensions PHP/Docker |
| **Git** | 2.x | Versioning, workflow feature branches |

### 7.2 Compatibilité navigateurs

- Chrome
- Firefox
- **Mobile** : Chrome Android

### 7.3 Performance

- Temps de réponse API : < 300 ms pour les requêtes courantes
- Recherche AJAX : résultats affichés < 500 ms après la frappe
- Images jeux : redimensionnées à la volée, < 500 Ko après upload

---

## 8. Contraintes de Sécurité

Conformément aux attentes du jury DWWM et aux bonnes pratiques OWASP :

| Risque | Mesure de protection |
|--------|---------------------|
| **Injection SQL** | Utilisation exclusive de requêtes préparées PDO (`prepare()` + `execute()`) |
| **XSS (Cross-Site Scripting)** | `htmlspecialchars()` sur toutes les sorties utilisateur + en-tête CSP |
| **CSRF (Cross-Site Request Forgery)** | Token CSRF unique par formulaire, vérifié côté serveur |
| **Brute-force** | Rate-limiting sur les endpoints d'authentification (max 5 tentatives / 15 min) |
| **Hashage des mots de passe** | `password_hash()` avec l'algorithme `PASSWORD_BCRYPT` (coût ≥ 12) |
| **Upload de fichiers** | Validation du type MIME réel (pas seulement l'extension), stockage hors `/public` |
| **Sessions PHP** | Flags `httpOnly`, `Secure`, `SameSite=Strict` sur le cookie de session |
| **En-têtes HTTP** | `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy` |
| **Variables d'environnement** | Mots de passe et clés dans `.env`, jamais en dur dans le code, `.env` dans `.gitignore` |

---

## 9. Exigences de Qualité

### 9.1 Accessibilité

- Structure HTML5 sémantique (`<header>`, `<main>`, `<nav>`, `<article>`, etc.)
- Attributs `alt` renseignés sur toutes les images
- Contrastes de couleurs conformes WCAG 2.1 niveau AA
- Navigation au clavier fonctionnelle
- Attributs ARIA sur les composants interactifs dynamiques

### 9.2 Responsive Design

- Approche **mobile-first** (breakpoints CSS : 576px, 768px, 992px, 1200px)
- Testé sur Chrome DevTools (modes iPhone SE, iPhone 14, iPad, desktop)
- Score Lighthouse Performance ≥ 70 (en développement local)

### 9.3 Qualité du code

- Architecture MVC respectée (pas de logique SQL dans les vues)
- Autoloading PSR-4 via Composer
- Validation des données côté client **ET** côté serveur (jamais seulement côté client)
- Aucune information sensible en clair dans le code versionné
- Commits atomiques avec messages descriptifs (Convention : `feat:`, `fix:`, `docs:`, etc.)

---

## Annexe — Contraintes liées au Dossier Projet DWWM

> Ce projet couvre les **8 compétences professionnelles** (CP1 à CP8) du titre DWWM :

| Compétence | Couverture dans GameVault |
|-----------|--------------------------|
| **CP1** — Installer et configurer son environnement | Docker, VS Code, Git, PHP, MySQL |
| **CP2** — Maquetter des interfaces | Figma, wireframes, charte graphique, arborescence |
| **CP3** — Réaliser des interfaces statiques | HTML5 sémantique, CSS3 responsive (Flexbox/Grid), accessibilité |
| **CP4** — Développer la partie dynamique | JavaScript vanilla, fetch API, AJAX, validation formulaires |
| **CP5** — Mettre en place une BDD relationnelle | MCD/MLD, scripts SQL, jeu de données, phpMyAdmin |
| **CP6** — Composants d'accès aux données | PDO, requêtes préparées, JOIN/GROUP BY, Redis/MongoDB |
| **CP7** — Composants métier serveur | MVC, auth bcrypt, API REST, logique sessions/votes, sécurité |
| **CP8** — Documenter le déploiement | Dockerfile, docker-compose, GitHub Actions CI/CD |

---

*Cahier des charges rédigé dans le cadre du Dossier Projet DWWM — GameVault v1.0*
