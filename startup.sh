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

cd /home/site/wwwroot

# Note: The root index.php will handle Azure App Service routing automatically
echo "TechBuy Laravel Application - Azure initialization starting..."
cp -f /home/site/wwwroot/public/robots.txt /home/site/wwwroot/ 2>/dev/null || true

# Install dependencies if needed
if [ ! -d "vendor" ]; then
    echo "Installing composer dependencies..."
    composer install --optimize-autoloader --no-dev
fi

# Laravel optimization commands
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations and seeders
echo "Running database migrations..."
php artisan migrate --force
php artisan db:seed --force

echo "TechBuy Laravel application startup completed successfully!"

# Keep the process running (Azure handles PHP-FPM)
tail -f /dev/null
