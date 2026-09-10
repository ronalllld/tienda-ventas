# syntax=docker/dockerfile:1

# ---- Etapa 1: dependencias PHP (mismas extensiones que la imagen final para evitar mismatches) ----
FROM php:8.2-fpm-alpine AS composer_deps

RUN apk add --no-cache libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring zip opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader --no-interaction
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---- Etapa 2: assets de frontend (Vue + Vite) ----
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
# Variables VITE_* se incrustan en el build, por eso deben pasarse como build-arg.
ARG VITE_NUMERO_WHATSAPP
ENV VITE_NUMERO_WHATSAPP=${VITE_NUMERO_WHATSAPP}
RUN npm run build

# ---- Etapa 3: imagen final (PHP-FPM + Nginx + Supervisor) ----
FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx supervisor curl libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring zip opcache

WORKDIR /var/www/html

COPY --from=composer_deps /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/laravel.ini
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Nginx corre como www-data; le damos sus propias carpetas temporales para
# no depender de las que trae Alpine por defecto (dueño "nginx", no escribibles).
RUN mkdir -p /tmp/nginx_client_body /tmp/nginx_proxy /tmp/nginx_fastcgi \
    && chown -R www-data:www-data /tmp/nginx_client_body /tmp/nginx_proxy /tmp/nginx_fastcgi

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
