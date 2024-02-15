# Use the Alpine Linux base image
FROM php:8.2-fpm-alpine

# Install required packages
RUN apk update && apk --no-cache add postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql

# Create a directory for PHP-FPM sock file
RUN mkdir -p /run/php

# Start Nginx and PHP-FPM
CMD ["php-fpm"]