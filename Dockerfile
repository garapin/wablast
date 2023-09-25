# Use the official PHP image with FPM
FROM php:8.2

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    git \
    unzip \
    libzip-dev \
    curl \
    supervisor && \
    docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs npm


# Verify Node.js and npm installations
RUN node -v && npm -v

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory in Docker
WORKDIR /var/www

# Copy the existing application directory permissions
COPY --chown=www-data:www-data . .

# Run composer and artisan commands
RUN composer update --no-dev && php artisan config:cache 

# Run npm commands
RUN npm install 

# Copy supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 9000 for FPM
EXPOSE 9000

# Start PHP-FPM and Node.js server with supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]