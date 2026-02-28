#!/bin/sh
set -e

# Create required log directories
mkdir -p /var/log/supervisor
mkdir -p /var/log/nginx

# Fix storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    sleep 2
    echo "MySQL not ready yet..."
done
echo "MySQL is ready!"

# Run Laravel setup commands
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start supervisor (manages nginx + php-fpm + queue)
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
