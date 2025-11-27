# Use the official PHP-Apache image
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy custom Apache config
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Copy the entire project into /var/www/html
COPY . /var/www/html/

# Give permissions to Apache user
RUN chown -R www-data:www-data /var/www/html
