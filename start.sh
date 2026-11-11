#!/bin/bash
set -e

PORT="${PORT:-80}"

echo "Configuring Apache to listen on port ${PORT}..."
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Set permissions
chown -R www-data:www-data /var/www/html/uploads
chmod -R 775 /var/www/html/uploads

echo "Starting Apache Web Server on port ${PORT}..."
exec apache2-foreground

