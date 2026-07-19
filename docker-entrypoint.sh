#!/bin/sh

set -e

echo "Clearing cache..."
php artisan optimize:clear

echo "Linking storage..."
php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force

echo "Clearing optimization configs..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

echo "Configuring Apache port..."
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/000-default.conf

echo "Starting Apache..."
exec apache2-foreground