FROM php:8.2-apache

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libxml2-dev \
    zlib1g-dev \
    libonig-dev \
    build-essential \
    curl \
    && docker-php-ext-install pdo pdo_pgsql mbstring tokenizer xml ctype json \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le projet dans le conteneur
COPY . /var/www/html/

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Exposer le port 80
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
