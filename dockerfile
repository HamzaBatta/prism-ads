# Use official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application files (except those in .dockerignore)
COPY . .

# Install PHP dependencies (optimized for Docker caching)
RUN composer install --no-dev --optimize-autoloader

# Permissions (adjust as needed)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
