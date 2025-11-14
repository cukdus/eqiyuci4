FROM php:8.2-apache

# ------------------------------
# Install dependencies
# ------------------------------
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    libxml2-dev libonig-dev libcurl4-openssl-dev \
    ca-certificates \
    cron supervisor \
 && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install gd zip pdo_mysql mysqli intl bcmath dom mbstring curl \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# ------------------------------
# Prepare Apache DocumentRoot
# ------------------------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf && \
    sed -ri 's!AllowOverride None!AllowOverride All!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ------------------------------
# Copy Composer
# ------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ------------------------------
# Install PHP dependencies
# ------------------------------
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress || true

# ------------------------------
# Add Cron schedule safely
# ------------------------------
COPY docker/cron /etc/cron.d/app-cron
RUN chmod 0644 /etc/cron.d/app-cron \
 && echo "" >> /etc/cron.d/app-cron \
 && crontab /etc/cron.d/app-cron

# ------------------------------
# Add Supervisor config
# ------------------------------
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ------------------------------
# Start Supervisor (runs Apache + Cron)
# ------------------------------
CMD ["/usr/bin/supervisord", "-n"]
