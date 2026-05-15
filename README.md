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

## Installation rapide

```bash
git clone https://github.com/qunetinL/GameVault.git
cd GameVault
./setup.sh
```

Le script configure automatiquement Docker, la base de données et les dépendances.

📖 **Guide complet de déploiement** : voir [DEPLOYMENT.md](DEPLOYMENT.md)

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
L'application requiert une vérification d'email lors de l'inscription. Tous les emails sont capturés par **Mailpit** (`http://localhost:8025`).

---
> Pour la mise en ligne, la configuration des ports et plus de détails, voir [DEPLOYMENT.md](DEPLOYMENT.md).