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

echo "Starting server..."
exec php -S 0.0.0.0:$PORT -t public