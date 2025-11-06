# Base image: PHP 8.2 + Apache
FROM php:8.2-apache

# Install required extensions and tools
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
 && docker-php-ext-install pdo_mysql mysqli \
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
ENV CI_ENVIRONMENT=production
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]