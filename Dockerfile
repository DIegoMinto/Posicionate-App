FROM php:8.4-fpm

# 1. Instalar dependencias del sistema y herramientas de compilación
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    zip unzip git curl nodejs npm \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd intl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configuración de subida en PHP
RUN echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/uploads.ini

# 3. Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 4. Copiar código fuente
COPY . .

# 5. Instalar dependencias PHP y compilar Assets
RUN composer install --no-dev --optimize-autoloader
RUN rm -rf node_modules package-lock.json \
    && npm install \
    && npm run build

# 6. Permisos de Laravel
RUN chmod -R 777 storage bootstrap/cache

# 7. Configurar Nginx y Supervisor
COPY nginx-render.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 8. Configurar Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]