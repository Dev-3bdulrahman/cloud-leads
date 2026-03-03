#!/bin/sh
set -e

echo "Preparing Laravel environment..."

# Ensure all required cache directories exist
echo "Creating cache directories..."
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/bootstrap/cache

# Ensure permissions are correct at runtime for storage/cache
echo "Fixing permissions..."
find /var/www/html/storage /var/www/html/bootstrap/cache -type d -exec chmod 775 {} +
find /var/www/html/storage /var/www/html/bootstrap/cache -type f -exec chmod 664 {} +
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Clear caches to avoid 502/errors from old views
php artisan view:clear || true
php artisan config:clear || true
php artisan cache:clear || true

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Create relative storage link (works in both host and container)
echo "Creating storage symlink..."
rm -rf /var/www/html/public/storage
ln -s ../storage/app/public /var/www/html/public/storage

# Run migrations
echo "Running migrations..."
php artisan migrate --force

echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
