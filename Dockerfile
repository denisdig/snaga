# 1️⃣ Base PHP 8.2 CLI
FROM php:8.2-cli

# 2️⃣ Définir le répertoire de travail
WORKDIR /app

# 3️⃣ Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libonig-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql intl mbstring zip \
    && apt-get clean

# 4️⃣ Installer Composer (gestionnaire PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5️⃣ Copier tous les fichiers du projet
COPY . .

# 6️⃣ Définir l'environnement Symfony en production
ENV APP_ENV=prod
ENV APP_DEBUG=0

# 7️⃣ Installer les dépendances PHP (sans dev pour la prod)
RUN composer install --no-dev --optimize-autoloader

# 8️⃣ Vider et précharger le cache Symfony pour prod
RUN php bin/console cache:clear --env=prod
RUN php bin/console cache:warmup --env=prod

# 9️⃣ Exposer le port attendu par Render
EXPOSE 10000

# 🔟 Lancer Symfony en serveur intégré sur Render
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
