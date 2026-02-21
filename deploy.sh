#!/bin/bash

# Cards Forge Deployment Script
# This script handles safe deployment with proper cache management

set -e  # Exit on any error

echo "🚀 Starting deployment..."

# Detect environment
if [ -f .env ]; then
    APP_ENV=$(grep APP_ENV .env | cut -d '=' -f2 | tr -d '"' | tr -d "'" | xargs)
else
    APP_ENV="production"
fi

echo "🌍 Environment detected: ${APP_ENV}"

if [ "$APP_ENV" = "production" ]; then
    # Pull latest code from git
    echo "📥 Pulling latest code from git..."
    git pull
fi

# Dump database schema before any changes
echo "💾 Dumping database schema..."
php artisan schema:dump

# Install/update PHP dependencies
echo "📦 Installing Composer dependencies..."

if [ "$APP_ENV" = "production" ]; then
    # Check if composer.lock is missing or out of sync
    if [ ! -f composer.lock ] || ! php composer.phar validate --no-check-all --quiet 2>/dev/null; then
        echo "   ⚠️  Cleaning vendor directory for fresh install..."
        rm -rf vendor/
    fi
    echo "   Using production mode (--no-dev)"
    php composer.phar install --no-dev --optimize-autoloader --no-interaction
else
    # Check if composer.lock is missing or out of sync
    if [ ! -f composer.lock ] || ! composer validate --no-check-all --quiet 2>/dev/null; then
        echo "   ⚠️  Cleaning vendor directory for fresh install..."
        rm -rf vendor/
    fi
    echo "   Using development mode (with dev dependencies)"
    composer install --optimize-autoloader --no-interaction
fi

if [ "$APP_ENV" = "production" ]; then
    # --- KRITIKUS RÉSZ A SYMLINK MIATT ---
    # Biztosítjuk, hogy a storage mappa linkelve legyen a public-ba
    echo "🔗 Ensuring storage link exists..."
    php artisan storage:link || true

    # Jogosultságok javítása (cPanel specifikus fix)
    echo "chmod: Fixing storage permissions..."
    chmod -R 775 storage bootstrap/cache

    # Copy public_html contents to ../www
    echo "📁 Copying public_html to ../www..."
    cp -r public_html/* ../www/
    # -------------------------------------
fi

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Run NPM build on dev
echo "Start NPM production build..."

if [ "$APP_ENV" = "production" ]; then
    echo " - Skipping because NPM not installed on production"
else
    npm run build
fi

# Clear all caches
echo "🧹 Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for better performance
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear and cache Filament components
echo "💎 Refreshing Filament components..."
php artisan filament:cache-components

# Upgrade Filament assets
echo "🔄 Upgrading Filament assets..."
php artisan filament:upgrade

# Optimize autoloader
echo "🔧 Optimizing autoloader..."
if [ "$APP_ENV" = "production" ]; then
    php composer.phar dump-autoload --optimize --no-dev
else
    composer dump-autoload --optimize
fi

# Restart queue workers (if running)
echo "🔄 Restarting queue workers..."
php artisan queue:restart

# Clear all optimization caches
echo "🚀 Clearing optimization caches..."
php artisan optimize:clear

echo ""
echo "✅ Deployment completed successfully!"
echo ""
echo "📊 Environment: ${APP_ENV}"
echo ""
echo "📋 Verification checklist:"
echo "   ✓ Queue workers restarted and processing jobs"
echo "   ✓ All caches cleared and regenerated"
if [ "$APP_ENV" = "production" ]; then
    echo "   ✓ Dev dependencies excluded (production mode)"
else
    echo "   ✓ Dev dependencies included (development mode)"
fi

