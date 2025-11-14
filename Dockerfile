FROM php:8.2-apache

# ------------------------------
# Install dependencies
# ------------------------------
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    libxml2-dev libonig-dev libcurl4-openssl-dev \
    libicu-dev \
    ca-certificates \
    cron supervisor \
 && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install gd zip pdo_mysql mysqli intl bcmath dom mbstring curl \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# ------------------------------
# Apache DocumentRoot -> /public
# ------------------------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf && \
    sed -ri 's!AllowOverride None!AllowOverride All!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ------------------------------
# Copy php.ini
# ------------------------------
COPY docker/php.ini /usr/local/etc/php/php.ini

# ------------------------------
# Set working directory
# ------------------------------
WORKDIR /var/www/html

# ------------------------------
# Copy source code
# ------------------------------
COPY . /var/www/html

# ------------------------------
# Composer: Install dependencies
# ------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress || true

# ------------------------------
# Fix permissions
# ------------------------------
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 /var/www/html/writable

# ------------------------------
# Cron setup
# ------------------------------
COPY docker/cron /etc/cron.d/app-cron
RUN chmod 0644 /etc/cron.d/app-cron \
 && crontab /etc/cron.d/app-cron

# ------------------------------
# Supervisor configuration
# ------------------------------
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ------------------------------
# Start Supervisor (runs Apache + Cron)
# ------------------------------
CMD ["/usr/bin/supervisord", "-n"]

