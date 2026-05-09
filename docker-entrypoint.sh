#!/bin/sh

set -e

echo "Clearing cache..."
php artisan optimize:clear

echo "Linking storage..."
php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force

echo "Caching config..."
php artisan config:cache
php artisan route:cache || true
php artisan view:cache

echo "Starting server..."
exec php -S 0.0.0.0:10000 -t public