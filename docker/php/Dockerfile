FROM php:8.4-fpm-alpine

WORKDIR /var/www

RUN apk update
# Базовые утилиты
RUN apk add --no-cache git \
    curl \
    unzip \
    bash

# Зависимости для PHP
RUN apk add --no-cache icu-dev
RUN apk add --no-cache libzip-dev
RUN apk add --no-cache libpng-dev
RUN apk add --no-cache libjpeg-turbo-dev
RUN apk add --no-cache freetype-dev
RUN apk add --no-cache libxml2-dev
RUN apk add --no-cache oniguruma-dev

# Для Xdebug (необязательно в продакшене)
RUN apk add --no-cache autoconf
RUN apk add --no-cache g++
RUN apk add --no-cache make
RUN apk add --no-cache linux-headers

# PostgreSQL клиент и зависимости для pdo_pgsql
RUN apk add --no-cache postgresql-dev \
    postgresql-client

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install gd
RUN docker-php-ext-install xml
RUN docker-php-ext-install sockets

RUN pecl install redis && docker-php-ext-enable redis

ENV PHP_IDE_CONFIG="serverName=maxmoll-test-app"

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.log=/var/logs/php/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=phpstorm" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
