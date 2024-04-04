FROM php:8.3-apache
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip mysqli

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY . .

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist

EXPOSE 80
