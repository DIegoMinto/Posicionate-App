FROM php:8.4-cli

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip unzip git curl \
    nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias Node + build
RUN npm install && npm run build

# Permisos Laravel
RUN chmod -R 777 storage bootstrap/cache

# Entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Puerto Render
EXPOSE 10000

# ENTRYPOINT (CLAVE)
ENTRYPOINT ["docker-entrypoint.sh"]
