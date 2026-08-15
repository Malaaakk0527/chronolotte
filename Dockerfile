FROM php:8.2-fpm-alpine AS app

# Dépendances système + extensions PHP (pgsql, pdo_pgsql, etc.)
RUN apk add --no-cache \
        libpq-dev nginx supervisor \
        oniguruma-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev \
        icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql pgsql mbstring zip gd intl opcache exif \
    && apk del --purge oniguruma-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev icu-dev

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie du code applicatif
COPY . .

# Permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Optimisations
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --prefer-dist

# Nginx : sert le front controller
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor.d/conf.d/supervisord.conf

EXPOSE 8080

RUN chmod +x docker/entrypoint.sh

CMD ["/bin/sh", "/var/www/html/docker/entrypoint.sh"]
