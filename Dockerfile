# syntax=docker/dockerfile:1.7

FROM php:8.3-fpm-alpine

# System deps
RUN apk add --no-cache \
    bash curl git icu-dev oniguruma-dev libzip-dev \
    libpng-dev freetype-dev libjpeg-turbo-dev \
    autoconf make g++ zip unzip

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
     pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Redis extension
RUN pecl install redis \
  && docker-php-ext-enable redis

# Opcache (sane defaults for dev; production can tune)
RUN { \
  echo "opcache.enable=1"; \
  echo "opcache.memory_consumption=256"; \
  echo "opcache.interned_strings_buffer=16"; \
  echo "opcache.max_accelerated_files=20000"; \
  echo "opcache.validate_timestamps=1"; \
  echo "opcache.revalidate_freq=1"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# PHP ini defaults
RUN { \
  echo "memory_limit=512M"; \
  echo "upload_max_filesize=50M"; \
  echo "post_max_size=50M"; \
  echo "max_execution_time=120"; \
  echo "date.timezone=UTC"; \
} > /usr/local/etc/php/conf.d/99-custom.ini

WORKDIR /var/www/html

# Install Composer (global)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ensure proper permissions for storage and cache at runtime
RUN addgroup -g 1000 www && adduser -G www -g www -s /bin/sh -D www \
  && chown -R www:www /var/www/html
USER www

# Expose FPM port
EXPOSE 9000

CMD ["php-fpm", "-F"]