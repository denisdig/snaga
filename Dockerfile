# Utiliser PHP 8.2 avec Apache
FROM php:8.2-apache

# Installer extensions nécessaires pour Symfony et MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier tout le projet dans le conteneur
COPY . /var/www/html/

# Définir le dossier de travail
WORKDIR /var/www/html

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Exposer le port 80 (Apache)
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
