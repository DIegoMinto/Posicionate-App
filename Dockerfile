FROM php:8.4-cli

# Dependencias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Permisos
RUN chmod -R 777 storage bootstrap/cache

# Build frontend
RUN apt-get install -y nodejs npm \
    && npm install \
    && npm run build

# Puerto de Render
EXPOSE 10000

# Comando para producción
CMD php artisan serve --host=0.0.0.0 --port=10000
