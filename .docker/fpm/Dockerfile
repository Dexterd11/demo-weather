FROM php:8.0-fpm-alpine

RUN set -xe \
  && apk update \
    && apk add oniguruma-dev autoconf zlib-dev make postgresql-dev gcc g++ libzip-dev \
    && docker-php-ext-install \
        bcmath \
        pcntl \
        mbstring \
        sysvsem \
        zip \
        pdo \
        pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
   && chmod +x /usr/local/bin/composer \
   && composer clear-cache
