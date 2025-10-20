# Utiliser PHP 8.2 avec Apache
FROM php:8.2-apache

# Installer extensions PHP nécessaires pour Symfony et PostgreSQL
RUN apt-get update && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring tokenizer xml ctype json

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier tout le projet dans le conteneur
COPY . /var/www/html/

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Exposer le port 80 (Apache)
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
