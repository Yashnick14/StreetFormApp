# Stage 1: builder
FROM composer:2.7 AS composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

# Stage 2: php
FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y \
    nginx curl git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql zip exif bcmath

# Copy composer from builder
COPY --from=composer /app /var/www/html

WORKDIR /var/www/html

# Copy rest of app
COPY . /var/www/html

# Install composer dependencies (if you need runtime composer)
RUN php -r "copy('https://getcomposer.org/installer','composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

# Start php-fpm and nginx using a small supervisor script
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
