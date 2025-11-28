# docker/Dockerfile-scheduler
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www

# Run Laravel scheduler loop
CMD ["sh", "-c", "while true; do php artisan schedule:run --verbose; sleep 60; done"]
