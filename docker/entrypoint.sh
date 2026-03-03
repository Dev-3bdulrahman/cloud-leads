#!/bin/sh
set -e

# Create required log directories
mkdir -p /var/log/supervisor
mkdir -p /var/log/nginx

# Fix storage permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for MySQL to be ready with a limit
echo "Waiting for MySQL (Host: $DB_HOST)..."
MAX_TRIES=30
COUNT=0
until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_TRIES ]; then
        echo "Error: MySQL is not reachable after $MAX_TRIES attempts."
        echo "Tip: Check if DB_HOST is correct (it shouldn't be 127.0.0.1 in Docker)."
        break
    fi
    sleep 2
    echo "MySQL not ready yet (Attempt $COUNT/$MAX_TRIES)..."
done
echo "MySQL check finished."

# Run Composer install if vendor is missing or as requested
if [ ! -d "vendor" ]; then
    echo "Vendor directory missing. Running composer install..."
    composer install --optimize-autoloader --no-dev --no-interaction
else
    echo "Vendor directory exists."
    # Optional: composer install --optimize-autoloader --no-dev --no-interaction
fi

# Run Laravel setup commands
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations and seeders
echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force || echo "Warning: Seeding failed. Continuing anyway..."

# Debug: list listening ports
echo "Checking listening ports..."
netstat -tln || echo "netstat not found"

# Check Nginx config
echo "Testing Nginx configuration..."
nginx -t

# Start supervisor (manages nginx + php-fpm + queue)
echo "Starting services via Supervisor..."
exec supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
