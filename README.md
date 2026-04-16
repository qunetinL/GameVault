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
2. (Optionnel) Copiez le fichier d'environnement et ajustez les ports si besoin :
   ```bash
   cp .env.example .env
   # Modifiez APP_PORT, PHPMYADMIN_PORT, etc. si les ports par défaut sont déjà utilisés
   ```
3. Lancez les conteneurs :
   ```bash
   docker-compose up -d --build
   ```
4. Accès aux services :
   - 🌐 **Application** : `http://localhost:8080` (ou port défini dans `APP_PORT`)
   - 🐘 **PhpMyAdmin** : `http://localhost:8081` (ou port défini dans `PHPMYADMIN_PORT`)
   - 📧 **Mailpit (Emails)** : `http://localhost:8025` (ou port défini dans `MAILPIT_UI_PORT`)

## 🔑 Comptes de Test et Administration

Pour tester l'application sans créer de compte, vous pouvez utiliser les identifiants suivants (chargés via `seed.sql`) :

### Administrateur
- **Email** : `admin@gamevault.com`
- **Mot de passe** : `GameVault2026!`
- **Accès** : Donne accès au panneau `/admin` (gestion utilisateurs et jeux).

### Utilisateurs Standards
| Email | Mot de passe |
|---|---|
| `alex@example.com` | `AlexGamer42#` |
| `sarah@example.com` | `SarahStream99@` |

## 📧 Vérification d'Email
L'application requiert une vérification d'email lors de l'inscription. Tous les emails envoyés sont capturés par **Mailpit**. Pour vérifier un compte :
1. Inscrivez-vous sur l'application.
2. Allez sur `http://localhost:8025`.
3. Cliquez sur le lien de confirmation dans l'email reçu.

---
> Les ports sont configurables via le fichier `.env` (`APP_PORT`, `PHPMYADMIN_PORT`, `MYSQL_PORT`, `REDIS_PORT`, `MAILPIT_UI_PORT`).