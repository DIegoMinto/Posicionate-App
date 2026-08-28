#!/bin/sh
set -e

echo "Linking storage..."
php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force

echo "Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ajustar dinámicamente el puerto de Nginx al puerto que Render asigna ($PORT)
if [ -n "$PORT" ]; then
    echo "Configuring Nginx to listen on port $PORT..."
    sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/sites-available/default
fi

# Inicia Supervisor (que a su vez arrancará PHP-FPM y Nginx)
exec "$@"