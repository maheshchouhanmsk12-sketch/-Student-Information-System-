FROM php:8.2-apache

# Install required system dependencies and PHP extensions (mysqli, gd)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite headers

# Set Apache DocumentRoot and permissions
WORKDIR /var/www/html

# Copy project files from sis folder
COPY sis/ /var/www/html/

# Copy startup script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Set directory permissions for uploads
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/uploads

# Expose standard port (overridden dynamically by start.sh using $PORT)
EXPOSE 80 10000

CMD ["/usr/local/bin/start.sh"]

