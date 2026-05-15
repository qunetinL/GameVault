# Deploiement GameVault

## Prerequis

- [Docker](https://docs.docker.com/get-docker/) et `docker compose`
- Git

## Installation rapide

```bash
git clone https://github.com/qunetinL/GameVault.git
cd GameVault
./setup.sh
```

Le script `setup.sh` effectue automatiquement :
1. Copie `.env.example` vers `.env` (si absent)
2. Construit et demarre les conteneurs Docker
3. Attend que MySQL soit operationnel
4. Execute `database/schema.sql` (creation des tables)
5. Execute `database/seed.sql` (donnees de test)
6. Installe les dependances PHP via Composer

## Acces aux services

| Service | URL | Port configurable |
|---------|-----|-------------------|
| Application | http://localhost:8080 | `APP_PORT` |
| PhpMyAdmin | http://localhost:8081 | `PHPMYADMIN_PORT` |
| Mailpit (Emails) | http://localhost:8025 | `MAILPIT_UI_PORT` |

Les ports sont configurables dans le fichier `.env`.

## Comptes de test

| Role | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@gamevault.com | GameVault2026! |
| Utilisateur | alex@example.com | AlexGamer42# |
| Utilisateur | sarah@example.com | SarahStream99@ |

Le compte admin donne acces au panneau `/admin`.

## Verification des emails

L'application requiert une verification d'email a l'inscription. Tous les emails sont captures par **Mailpit** :
1. Inscrivez-vous sur l'application
2. Allez sur http://localhost:8025
3. Cliquez sur le lien de confirmation

## Mise en ligne (Cloudflare Tunnel)

Pour rendre l'application accessible sur internet sans serveur dedie :

```bash
# Installation
# Arch Linux
sudo pacman -S cloudflared
# Debian/Ubuntu
curl -fsSL https://pkg.cloudflare.com/cloudflare-main.gpg | sudo tee /usr/share/keyrings/cloudflare-main.gpg >/dev/null
sudo apt install cloudflared
```

```bash
# Lancement (les conteneurs Docker doivent etre demarres)
cloudflared tunnel --url http://localhost:8080
```

Une URL publique `https://xxxxx.trycloudflare.com` est generee automatiquement. L'URL change a chaque relance.

## Reinitialisation de la base de donnees

Pour repartir de zero (re-appliquer schema + seed) :

```bash
docker compose exec -T db mysql -u root -proot gamevault < database/schema.sql
docker compose exec -T db mysql -u root -proot gamevault < database/seed.sql
```

## Structure Docker

- `nginx` — Serveur web (reverse proxy vers PHP-FPM)
- `php` — PHP 8.2-FPM avec extensions PDO, GD, Redis
- `db` — MySQL 8.0
- `redis` — Cache et sessions temps reel
- `phpmyadmin` — Interface d'administration BDD
- `mailpit` — Serveur SMTP de test
