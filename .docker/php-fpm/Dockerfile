FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
    nano \
    libpq-dev \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . /var/www/html