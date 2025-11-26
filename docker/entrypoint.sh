#!/bin/sh
set -e
mkdir -p /var/www/html/writable/cache /var/www/html/writable/logs /var/www/html/writable/session /var/www/html/writable/cookies
mkdir -p /var/www/html/writable/klikbca/hasil /var/www/html/writable/klikbca/html
mkdir -p /var/www/html/public/uploads/avatars
chown -R www-data:www-data /var/www/html/writable /var/www/html/public/uploads
chmod -R 775 /var/www/html/writable /var/www/html/public/uploads
find /var/www/html/writable -type d -exec chmod 2775 {} + || true
find /var/www/html/writable -type f -exec chmod 664 {} + || true
if [ -f /var/www/html/composer.json ] && [ ! -d /var/www/html/vendor ]; then
  composer install --no-dev --prefer-dist --no-interaction --no-progress || true
fi
exec /usr/bin/supervisord
