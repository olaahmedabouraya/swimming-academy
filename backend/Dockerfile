# Use PHP 8.2 CLI (lighter weight for Laravel API)
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create Laravel directories (may be missing due to .dockerignore)
RUN mkdir -p /var/www/html/storage/framework/{sessions,views,cache} \
    /var/www/html/storage/logs \
    /var/www/html/storage/app/public \
    /var/www/html/bootstrap/cache

# Set permissions
RUN chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port (Render will set $PORT)
EXPOSE $PORT

# Start Laravel using artisan serve
CMD php artisan serve --host=0.0.0.0 --port=$PORT
