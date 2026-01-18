FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable mod_rewrite
RUN a2enmod rewrite

# Configure PHP to allow short open tags (<?) as used in dbinfo.php
RUN echo "short_open_tag = On" > /usr/local/etc/php/conf.d/short-tags.ini

# Configure PHP logging
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/logging.ini \
    && echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/logging.ini

# Set working directory
WORKDIR /var/www/html
