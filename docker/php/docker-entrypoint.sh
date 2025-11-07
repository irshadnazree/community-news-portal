#!/bin/sh
set -e

# Function to fix permissions
fix_permissions() {
    chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
}

# Fix permissions for existing directories
fix_permissions

# Ensure all storage subdirectories exist
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Fix permissions again after creating directories
fix_permissions

# Execute php-fpm
exec php-fpm -F

