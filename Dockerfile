FROM php:8.1-apache

WORKDIR /var/www/html

# Install minimal dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy composer files first
COPY composer.json /var/www/html/

# Install PHP dependencies
RUN cd /var/www/html && composer install --no-interaction --prefer-dist --no-dev 2>&1 || true

# Copy application files
COPY .htaccess /var/www/html/.htaccess
COPY .htrouter.php /var/www/html/.htrouter.php
COPY app /var/www/html/app
COPY public /var/www/html/public
COPY cache /var/www/html/cache
COPY index.html /var/www/html/index.html

# Copy Apache config
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/cache

EXPOSE 80

CMD ["apache2-foreground"]
