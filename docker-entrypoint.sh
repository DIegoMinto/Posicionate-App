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
php artisan db:seed --force

echo "Starting server..."
exec php artisan serve --host=0.0.0.0 --port=10000
