# ==========================================
# Stage 1: Build Frontend Assets using Node.js
# ==========================================
FROM docker.io/library/node:20-alpine AS node-builder
WORKDIR /app

# Install npm dependencies
COPY package*.json ./
RUN npm ci

# Copy application and build assets
COPY . .
RUN npm run build

# ==========================================
# Stage 2: Build Backend Dependencies using Composer
# ==========================================
FROM docker.io/library/php:8.2-alpine AS composer-builder
WORKDIR /app

# Install system utilities needed by composer
RUN apk add --no-cache git unzip zip libpq-dev

# Copy composer binary from official image
COPY --from=docker.io/library/composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files and download dependencies first (leveraging cache)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy code and generate optimized autoloader
COPY . .
RUN composer dump-autoload --no-dev --optimize

# ==========================================
# Stage 3: Final Production Image (PHP-FPM + Nginx)
# ==========================================
FROM docker.io/library/php:8.2-fpm-alpine
WORKDIR /var/www/html

# Install production system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-client \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_pgsql pgsql zip opcache

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Fix line endings of entrypoint script and make it executable (crucial when built from Windows)
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# Copy built application files from composer-builder stage
COPY --from=composer-builder /app /var/www/html

# Copy compiled frontend assets from node-builder stage
COPY --from=node-builder /app/public/build /var/www/html/public/build

# Set correct permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose HTTP port
EXPOSE 80

# Configure entrypoint and default command
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["web"]
