# Utiliser PHP 8.2 avec Apache et Debian Bullseye
FROM php:8.2-apache-bullseye

# Installer dépendances système nécessaires pour PostgreSQL et Symfony
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libxml2-dev \
    zlib1g-dev \
    libonig-dev \
    build-essential \
    curl \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql mbstring tokenizer xml ctype json

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le projet
COPY . /var/www/html/

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Exposer le port 80
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
