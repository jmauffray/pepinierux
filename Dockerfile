FROM php:5.6-apache

# Install mysql extension (removed in PHP 7, present in 5.6 but needs install)
RUN docker-php-ext-install mysql mysqli

# Enable mod_rewrite
RUN a2enmod rewrite

# Configure PHP to allow short open tags (<?) as used in dbinfo.php
RUN echo "short_open_tag = On" > /usr/local/etc/php/conf.d/short-tags.ini

# Set working directory
WORKDIR /var/www/html
