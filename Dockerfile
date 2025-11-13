# Base image: PHP 8.2 + Apache
FROM php:8.2-apache

# Install required extensions and tools
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    libxml2-dev libonig-dev libcurl4-openssl-dev \
    ca-certificates cron supervisor \
 && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install \
    gd \
    zip \
    pdo_mysql \
    mysqli \
    intl \
    bcmath \
    dom \
    mbstring \
    curl \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# Set Apache DocumentRoot to /var/www/html/public (CodeIgniter's public dir)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!DocumentRoot /var/www/html!DocumentRoot ${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri 's!<Directory /var/www/html>!<Directory ${APACHE_DOCUMENT_ROOT}>!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
 && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer (from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy app source
WORKDIR /var/www/html
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

# Prepare runtime directories and permissions
RUN mkdir -p public/uploads \
 && chown -R www-data:www-data writable public/uploads \
 && chmod -R 775 writable public/uploads

# Environment and ports
ENV CI_ENVIRONMENT=production \
    BCA_CACERT_PATH=/etc/ssl/certs/ca-certificates.crt
EXPOSE 80

# Copy Supervisor config and cron schedule
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/cron /etc/cron.d/app
RUN chmod 0644 /etc/cron.d/app \
 && touch /var/log/cron.log

# Ensure cron uses our schedule
RUN crontab /etc/cron.d/app

# Start Supervisor to run Apache and cron
CMD ["/usr/bin/supervisord", "-n"]