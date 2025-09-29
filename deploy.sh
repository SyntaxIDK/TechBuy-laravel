#!/bin/bash

# TechBuy Laravel Deployment Script
# This script helps you deploy to Azure while keeping local development intact

echo "🚀 TechBuy Laravel Azure Deployment Script"
echo "=========================================="

# Function to check if we're in the right directory
check_directory() {
    if [ ! -f "artisan" ]; then
        echo "❌ Error: Please run this script from your Laravel project root directory"
        exit 1
    fi
}

# Function to backup current .env (only if it's a local environment)
backup_env() {
    if [ -f ".env" ]; then
        # Check if current .env is local (not production)
        if grep -q "APP_ENV=local" .env || grep -q "DB_HOST=127.0.0.1" .env; then
            cp .env .env.local.backup
            echo "✅ Backed up local .env to .env.local.backup"
        else
            echo "⚠️  Current .env appears to be production - skipping backup"
            if [ ! -f ".env.local.backup" ] && [ -f ".env.local.original" ]; then
                cp .env.local.original .env.local.backup
                echo "✅ Using .env.local.original as backup"
            fi
        fi
    fi
}

# Function to use production environment
use_production_env() {
    if [ -f ".env.production" ]; then
        cp .env.production .env
        echo "✅ Switched to production environment (.env.production → .env)"
    else
        echo "❌ Error: .env.production not found!"
        exit 1
    fi
}

# Function to restore local environment
restore_local_env() {
    if [ -f ".env.local.backup" ]; then
        cp .env.local.backup .env
        echo "✅ Restored local environment (.env.local.backup → .env)"
    elif [ -f ".env.local.original" ]; then
        cp .env.local.original .env
        echo "✅ Restored local environment (.env.local.original → .env)"
    else
        echo "❌ Error: No local backup found!"
        echo "💡 Try creating .env.local.original with your local settings"
        exit 1
    fi
}

# Function to build for production
build_for_production() {
    echo "🔨 Building assets for production..."

    # Install production dependencies
    echo "📦 Installing production dependencies..."
    composer install --optimize-autoloader --no-dev

    # Clear and cache configs (skip cache:clear for production env to avoid DB connection issues)
    echo "🧹 Clearing and caching configurations..."
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear

    # Skip cache:clear for production environment to avoid Azure connection issues from local
    if grep -q "APP_ENV=local" .env; then
        php artisan cache:clear
    else
        echo "⚠️  Skipping cache:clear for production environment (will be done on Azure)"
    fi

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Build frontend assets
    echo "🎨 Building frontend assets..."
    npm install
    npm run build

    echo "✅ Production build complete!"
}

# Function to test configuration
test_config() {
    echo "🧪 Testing configuration..."
    php artisan config:show database.connections.pgsql.host
    php artisan config:show database.connections.mongodb
    echo "✅ Configuration test complete!"
}

# Main script logic
case "${1:-help}" in
    "deploy")
        echo "🚀 Preparing for Azure deployment..."
        check_directory
        backup_env
        use_production_env
        build_for_production
        test_config
        echo ""
        echo "✅ Ready for deployment!"
        echo "📋 Next steps:"
        echo "   1. Commit your changes: git add . && git commit -m 'Production build'"
        echo "   2. Push to Azure: git push azure main"
        echo "   3. Run './deploy.sh restore' after deployment to restore local env"
        ;;

    "restore")
        echo "🔄 Restoring local development environment..."
        check_directory
        restore_local_env

        # Reinstall dev dependencies
        echo "📦 Reinstalling development dependencies..."
        composer install
        npm install

        # Clear caches for local development
        php artisan config:clear
        php artisan route:clear
        php artisan view:clear
        php artisan cache:clear

        echo "✅ Local environment restored!"
        ;;

    "test-local")
        echo "🧪 Testing local configuration..."
        check_directory
        if [ ! -f ".env.local.backup" ]; then
            backup_env
        fi
        php artisan config:show database.connections.pgsql.host
        echo "✅ Local test complete!"
        ;;

    "test-production")
        echo "🧪 Testing production configuration..."
        check_directory
        backup_env
        use_production_env
        test_config
        restore_local_env
        echo "✅ Production test complete!"
        ;;

    "help"|*)
        echo "Usage: ./deploy.sh [command]"
        echo ""
        echo "Commands:"
        echo "  deploy           - Prepare and build for Azure deployment"
        echo "  restore          - Restore local development environment"
        echo "  test-local       - Test local database connections"
        echo "  test-production  - Test production database connections"
        echo "  help             - Show this help message"
        echo ""
        echo "Typical workflow:"
        echo "  1. ./deploy.sh deploy    # Prepare for deployment"
        echo "  2. git push azure main   # Deploy to Azure"
        echo "  3. ./deploy.sh restore   # Restore local environment"
        ;;
esac
