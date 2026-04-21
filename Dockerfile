FROM php:8.4-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    build-essential \
    autoconf \
    pkg-config \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Extensiones PHP base
RUN docker-php-ext-install pdo pdo_pgsql zip opcache

# === INSTALACIÓN DE REDIS (versión corregida y forzada para PHP 8.4) ===
RUN pecl update-channels \
    && pecl install -o -f redis \
    && docker-php-ext-enable redis

# Configuración de OPcache
COPY docker-config/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Permisos
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]