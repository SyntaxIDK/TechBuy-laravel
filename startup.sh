#!/bin/bash

# Azure App Service Linux Startup Script for Laravel
echo "Starting TechBuy Laravel Application..."

# Set proper permissions
chmod -R 755 /home/site/wwwroot
chmod -R 777 /home/site/wwwroot/storage
chmod -R 777 /home/site/wwwroot/bootstrap/cache

# Copy environment file if it exists
if [ -f /home/site/wwwroot/.env.production ]; then
    cp /home/site/wwwroot/.env.production /home/site/wwwroot/.env
    echo "Environment file copied"
fi

# Clear and cache configurations
cd /home/site/wwwroot

# Install dependencies if needed
if [ ! -d "vendor" ]; then
    composer install --optimize-autoloader --no-dev
fi

# Laravel optimization commands
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

echo "TechBuy Laravel application startup completed"

# Start PHP-FPM (this is handled by Azure, but we ensure Laravel is ready)
exec "$@"
