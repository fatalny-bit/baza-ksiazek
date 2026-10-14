FROM php:8.3-fpm-alpine

RUN apk add --no-cache oniguruma \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS oniguruma-dev \
    && docker-php-ext-install pdo_sqlite mbstring \
    && apk del .build-deps \
    && mkdir -p /var/www/html/database \
    && chown -R www-data:www-data /var/www/html/database

WORKDIR /var/www/html