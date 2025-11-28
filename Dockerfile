FROM php:8.2-apache

# Install packages
RUN apt-get update && apt-get install -y \
    cron \
    supervisor \
    tzdata \
    libzip-dev unzip git \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    libxml2-dev libonig-dev libcurl4-openssl-dev \
    libicu-dev \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

ENV TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Enable apache modules
RUN a2enmod rewrite headers

# Install PHP extensions
RUN docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_mysql mysqli intl gd zip curl mbstring xml

# Copy php.ini
COPY docker/php.ini /usr/local/etc/php/php.ini

# Supervisor & cron
COPY docker/cron /etc/cron.d/app-cron
RUN chmod 0644 /etc/cron.d/app-cron

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Apache DocumentRoot untuk CodeIgniter 4
RUN printf "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

RUN if [ -f /var/www/html/composer.json ]; then \
    composer install --no-dev --prefer-dist --no-interaction --no-progress; \
  fi

RUN mkdir -p /var/www/html/writable /var/www/html/writable/logs /var/www/html/writable/cache /var/www/html/writable/session /var/www/html/writable/cookies && \
    chown -R 1000:1000 /var/www/html/writable && \
    chmod -R 777 /var/www/html/writable


# Upload directory fix
RUN mkdir -p /var/www/html/public/uploads/avatars && \
    chown -R 1000:1000 /var/www/html/public/uploads && \
    chmod -R 777 /var/www/html/public/uploads


COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
EXPOSE 80
CMD ["/entrypoint.sh"]
