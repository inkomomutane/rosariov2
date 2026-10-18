
# syntax=docker/dockerfile:1

############################################
# Frontend build
############################################
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN --mount=type=cache,target=/root/.npm \
    npm ci --no-audit --no-fund

COPY . .

RUN npm run build


############################################
# Production: PHP 8.4 + FrankenPHP Alpine
############################################
FROM serversideup/php:8.4-frankenphp-alpine AS production

USER root

WORKDIR /var/www/html

ENV CADDY_HTTP_PORT=80 \
    PHP_OPCACHE_ENABLE=1

# Required PHP extensions
RUN install-php-extensions \
    gd \
    bcmath \
    exif \
    intl

# Application files
COPY --chown=www-data:www-data . .

# Compiled frontend
COPY --from=frontend \
    --chown=www-data:www-data \
    /app/public/build/ \
    /var/www/html/public/build/

# Production dependencies
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    COMPOSER_ALLOW_SUPERUSER=1 \
    composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --no-progress \
        --optimize-autoloader

# Laravel writable directories
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    public/excel-temp \
    && chown -R www-data:www-data \
        storage bootstrap/cache public/excel-temp

# Database migrations
COPY --chmod=755 \
    ci_config/entrypoint.d/99-migrate.sh \
    /etc/entrypoint.d/99-migrate.sh

USER www-data
