# FeedFlow – Starter Kit Officiel (Formation Laravel Avancé)

## Lancer le projet

```bash
# 1. Clonez le projet
git clone https://github.com/M-Thibaud/Feedflow-Starter-Kit.git
cd Feedflow-Starter-Kit

# 2. Copiez l'environnement
cp .env.example .env        # Windows : copy .env.example .env

# 3. Build des containers
docker compose build

# 4. Démarrez les conteneurs
docker compose up -d

# 5. Installez les dépendances et initialisez Laravel
docker exec -it feedflow-app bash -c "
  composer install --no-dev --optimize-autoloader --no-interaction &&
  php artisan key:generate --force &&
  php artisan storage:link && 
  php artisan migrate
"

# 6. Ajouter les données par défaut dans la base de données
docker exec -it feedflow-app php artisan db:seed
```

## Liens utiles

| Outil                        | URL                          | Identifiants / Info                      |
|------------------------------|------------------------------|------------------------------------------|
| Application                  | http://localhost:8000        | FeedFlow en cours                        |
| Emails (Mailpit)             | http://localhost:8025        | Voir toutes les notifications            |
| Base de données (PhpMyAdmin) | http://localhost:8080        | feedflow_user / feedflow2025 |

**Compte test** : `test@feedflow.local` / `password`

## Commandes Docker utiles

| Action                        | Commande                                                |
|-------------------------------|---------------------------------------------------------|
| Entrer dans le conteneur      | `docker exec -it feedflow-app bash`                     |
| Sortir du conteneur           | `exit`                                                  |
| Exécuter une commande Artisan | `docker exec -it feedflow-app php artisan `             |

**Important** : Vous pouvez entrer dans le conteneur pour saisir directement vos commandes artisan.