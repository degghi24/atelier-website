FROM php:8.4-apache

# Install extensions for MariaDB/MySQL connectivity.
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Use the development configuration for local development.
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Enable mod_rewrite for optional .htaccess rules.
RUN a2enmod rewrite