#!/bin/bash
set -e

PORT="${PORT:-80}"

echo "Configuring Apache to listen on port ${PORT}..."
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Crucial for Reverse Proxies (Render): Never append internal PORT to redirects
echo "UseCanonicalName Off" >> /etc/apache2/apache2.conf
echo "UseCanonicalPhysicalPort Off" >> /etc/apache2/apache2.conf
echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set permissions
chown -R www-data:www-data /var/www/html/uploads
chmod -R 775 /var/www/html/uploads

echo "Starting Apache Web Server on port ${PORT}..."
exec apache2-foreground
