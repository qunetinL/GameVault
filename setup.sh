#!/usr/bin/env bash
# ═══════════════════════════════════════════════════
# GameVault — Script d'installation automatique
# ═══════════════════════════════════════════════════

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

info()  { echo -e "${GREEN}[INFO]${NC} $1"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
error() { echo -e "${RED}[ERROR]${NC} $1"; exit 1; }

# 1. Verifier que Docker et docker-compose sont installes
command -v docker >/dev/null 2>&1 || error "Docker n'est pas installe. Voir https://docs.docker.com/get-docker/"
docker compose version >/dev/null 2>&1 || error "docker compose n'est pas disponible."

# 2. Copier .env.example -> .env si necessaire
if [ ! -f .env ]; then
    cp .env.example .env
    info "Fichier .env cree depuis .env.example"
else
    info "Fichier .env existant conserve"
fi

# 3. Lancer les conteneurs
info "Construction et demarrage des conteneurs..."
docker compose up -d --build

# 4. Attendre que MySQL soit pret
info "Attente de MySQL..."
RETRIES=30
until docker compose exec -T db mysqladmin ping -h localhost -u root -proot --silent 2>/dev/null; do
    RETRIES=$((RETRIES - 1))
    if [ $RETRIES -le 0 ]; then
        error "MySQL n'a pas demarre dans le temps imparti."
    fi
    sleep 2
done
info "MySQL est pret."

# 5. Executer le schema et le seed
info "Application du schema de base de donnees..."
docker compose exec -T db mysql -u root -proot gamevault < database/schema.sql

info "Chargement des donnees de test (seed)..."
docker compose exec -T db mysql -u root -proot gamevault < database/seed.sql

# 6. Installer les dependances Composer
info "Installation des dependances PHP (Composer)..."
docker compose exec -T php composer install --no-interaction --working-dir=/var/www/html

# 7. Resume
echo ""
info "============================================"
info "  GameVault est pret !"
info "============================================"
echo ""
echo -e "  Application  : ${GREEN}http://localhost:${APP_PORT:-8080}${NC}"
echo -e "  PhpMyAdmin   : ${GREEN}http://localhost:${PHPMYADMIN_PORT:-8081}${NC}"
echo -e "  Mailpit      : ${GREEN}http://localhost:${MAILPIT_UI_PORT:-8025}${NC}"
echo ""
echo -e "  Comptes de test :"
echo -e "    Admin  : admin@gamevault.com / GameVault2026!"
echo -e "    User 1 : alex@example.com / AlexGamer42#"
echo -e "    User 2 : sarah@example.com / SarahStream99@"
echo ""
