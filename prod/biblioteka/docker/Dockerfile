FROM php:8.3-fpm-alpine

RUN apk add --no-cache oniguruma sqlite-libs \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS oniguruma-dev sqlite-dev \
    && docker-php-ext-install pdo_sqlite mbstring \
    && apk del .build-deps \
    && mkdir -p /var/lib/biblioteka \
    && chown -R www-data:www-data /var/lib/biblioteka

WORKDIR /var/www/html