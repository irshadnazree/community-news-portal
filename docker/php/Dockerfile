FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    nano \
    tzdata \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

# Create storage and cache directories with proper permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/app/public \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R 755 /var/www

ENTRYPOINT ["docker-entrypoint.sh"]
