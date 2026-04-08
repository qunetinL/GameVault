# 🚀 Guide de Déploiement — GameVault

Ce guide explique comment déployer GameVault sur un serveur VPS (Arch Linux) et configurer les mises à jour automatiques.

## 1. Prérequis sur le Serveur (Arch Linux)

Connectez-vous à votre serveur et installez Docker :

```bash
# Mise à jour et installation de Docker + Docker Compose
sudo pacman -Syu
sudo pacman -S docker docker-compose git --noconfirm

# Démarrage et activation du service Docker
sudo systemctl enable --now docker.service
```

## 2. Configuration Initiale

Clonez le projet sur le serveur (dans `/var/www/GameVault`) :

```bash
sudo mkdir -p /var/www/GameVault
sudo chown $USER:$USER /var/www/GameVault
cd /var/www/GameVault
git clone https://github.com/VOTRE_USERNAME/GameVault.git .
```

## 3. Variables d'Environnement

Créez un fichier `.env` sur le serveur :

```bash
cp src/.env.example src/.env
# Modifiez les variables (mots de passe, URL, etc.)
nano src/.env
```

## 4. Configuration CI/CD (GitHub)

Pour que les mises à jour soient automatiques, ajoutez ces **Secrets** dans votre dépôt GitHub (`Settings > Secrets and variables > Actions`) :

- `SERVER_HOST` : L'adresse IP de votre serveur.
- `SERVER_USER` : Votre nom d'utilisateur SSH (souvent `root` ou `ubuntu`).
- `SSH_PRIVATE_KEY` : Votre clé privée SSH (assurez-vous d'avoir ajouté la clé publique correspondante sur le serveur dans `~/.ssh/authorized_keys`).

## 5. Comment ça marche ?

À chaque `git push origin main` :
1. GitHub Actions s'active.
2. Il se connecte en SSH à votre serveur.
3. Il exécute `git pull`.
4. Il reconstruit les images Docker avec les optimisations de production (`docker-compose.prod.yml`).
5. Votre site est mis à jour sans interruption !

---
*Note : Pour le SSL (HTTPS), il est recommandé d'ajouter Certbot (Let's Encrypt) sur le serveur ou d'utiliser un reverse proxy comme Traefik.*
