# syntax=docker/dockerfile:1.7

# ==========================================
# STAGE 1: Build Dependencies
# ==========================================
FROM composer:2 AS composer

FROM php:8.3-fpm-alpine AS base

# Install system dependencies in a single layer for better caching
RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    autoconf \
    g++ \
    make \
    openssl-dev \
    libxml2-dev \
    && rm -rf /var/cache/apk/*

# Install essential PHP extensions for Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        gd \
        zip \
        fileinfo \
        ctype \
        json \
        tokenizer

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure production-ready opcache settings
RUN { \
        echo "opcache.enable=1"; \
        echo "opcache.enable_cli=0"; \
        echo "opcache.memory_consumption=256"; \
        echo "opcache.interned_strings_buffer=16"; \
        echo "opcache.max_accelerated_files=20000"; \
        echo "opcache.validate_timestamps=0"; \
        echo "opcache.revalidate_freq=0"; \
        echo "opcache.fast_shutdown=1"; \
        echo "opcache.optimization_level=0x7FFEBFFF"; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Configure production PHP settings
RUN { \
        echo "memory_limit=512M"; \
        echo "upload_max_filesize=50M"; \
        echo "post_max_size=50M"; \
        echo "max_execution_time=120"; \
        echo "max_input_time=120"; \
        echo "date.timezone=UTC"; \
        echo "expose_php=0"; \
        echo "display_errors=0"; \
        echo "log_errors=1"; \
        echo "error_log=/var/log/php_errors.log"; \
        echo "session.gc_probability=0"; \
        echo "session.gc_divisor=1000"; \
    } > /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/html

# Create non-root user for security
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# ==========================================
# STAGE 2: Production Build
# ==========================================
FROM base AS production

# Copy application code
COPY src/ .

# Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create and set proper permissions for storage directories
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/app/public \
    bootstrap/cache \
    && chown -R www:www /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Remove development files for smaller image
RUN rm -rf \
    .git \
    .gitignore \
    .editorconfig \
    .env.example \
    *.md \
    tests/ \
    database/factories/ \
    database/seeders/ \
    node_modules/ \
    2>/dev/null || true

USER www

# Expose FPM port
EXPOSE 9000

# Health check for production
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php-fpm -t || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm", "-F"]