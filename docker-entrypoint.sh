#!/bin/sh

set -e

echo "Linking storage..."
php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force

echo "Configuring Apache for Render Proxy & HTTPS..."
# Activar módulo de headers en Apache si no está activo
a2enmod headers rewrite || true

# Indicar a Apache que confíe en el header HTTPS de Render
echo "SetEnvIf X-Forwarded-Proto \"https\" HTTPS=on" >> /etc/apache2/apache2.conf || true

echo "Configuring Apache port..."
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/000-default.conf

echo "Caching Laravel configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache..."
exec apache2-foreground