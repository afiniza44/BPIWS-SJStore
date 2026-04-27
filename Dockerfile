FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy package files and install Node deps
COPY package.json package-lock.json ./
RUN npm ci

# Copy the rest of the application
COPY . .

# Build frontend assets
RUN npm run build

# Run Laravel post-install scripts
RUN php artisan package:discover --ansi \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Fix storage permissions
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE $PORT

# PHP_CLI_SERVER_WORKERS enables multi-worker support in php -S (PHP 7.4+)
# This allows concurrent requests so heavy export jobs don't block other pages
CMD PHP_CLI_SERVER_WORKERS=8 php artisan migrate --force \
    && PHP_CLI_SERVER_WORKERS=8 php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
