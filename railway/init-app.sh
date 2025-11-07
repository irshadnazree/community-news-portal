#!/bin/bash
# Railway Laravel initialization script
# Based on Railway's official Laravel deployment guide

# Exit the script if any command fails
set -e

echo "Starting Laravel initialization..."

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Warning: Migrations failed, but continuing..."

# Clear all caches first
echo "Clearing caches..."
php artisan optimize:clear || true

# Cache the various components of the Laravel application
echo "Caching Laravel components..."
php artisan config:cache || true
php artisan event:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Laravel initialization complete!"

