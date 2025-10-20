# Utiliser PHP CLI avec Apache (ou juste CLI si tu veux le serveur intégré)
FROM php:8.2-cli

# Définir le répertoire de travail
WORKDIR /app

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libonig-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql intl mbstring zip \
    && apt-get clean

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier tous les fichiers du projet dans le container
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Exposer le port utilisé par Render
EXPOSE 10000

# Lancer le serveur Symfony
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
