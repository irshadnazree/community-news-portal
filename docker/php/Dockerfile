FROM php:8.3-fpm-alpine

# Install system dependencies in a single layer
RUN apk add --no-cache \
    bash \
    curl \
    git \
    tzdata \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    oniguruma-dev \
    libzip-dev \
    autoconf \
    g++ \
    make \
    openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
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

# Configure opcache for optimal performance
RUN { \
        echo "opcache.enable=1"; \
        echo "opcache.enable_cli=0"; \
        echo "opcache.memory_consumption=256"; \
        echo "opcache.interned_strings_buffer=16"; \
        echo "opcache.max_accelerated_files=20000"; \
        echo "opcache.validate_timestamps=1"; \
        echo "opcache.revalidate_freq=2"; \
        echo "opcache.fast_shutdown=1"; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP settings
RUN { \
        echo "memory_limit=512M"; \
        echo "upload_max_filesize=50M"; \
        echo "post_max_size=50M"; \
        echo "max_execution_time=120"; \
        echo "max_input_time=120"; \
        echo "date.timezone=UTC"; \
        echo " expose_php=0"; \
    } > /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/html

# Create proper user and set ownership
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set proper ownership for future volume mounts
RUN chown -R www:www /var/www/html

USER www

ENTRYPOINT ["docker-entrypoint.sh"]
