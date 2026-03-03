# Stage 1: Build Node.js assets
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
# Use npm install as a fallback since package-lock.json might be missing
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Final PHP + Nginx image
FROM php:8.2-fpm-alpine

LABEL maintainer="cloud.aaifgroup.com"

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    tzdata \
    $PHPIZE_DEPS

# Set timezone
ENV TZ=UTC
RUN cp /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ensure www-data user exists and Nginx uses it
RUN set -x \
    && addgroup -g 1000 -S www-data || true \
    && adduser -u 1000 -D -S -G www-data www-data || true \
    && sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf

# Remove default PHP-FPM configurations to avoid conflicts
RUN rm -f /usr/local/etc/php-fpm.d/*.conf /usr/local/etc/php-fpm.d/*.conf.default

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built assets from Stage 1
COPY --from=node-builder /app/public/build ./public/build

# Install PHP dependencies (production)
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-progress

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure /var/run is writable for Nginx/PID files
RUN mkdir -p /var/run && chown -R www-data:www-data /var/run

# Copy configuration files (ensure these paths exist in your repo)
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copy and set entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
