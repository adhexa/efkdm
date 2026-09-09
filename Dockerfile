FROM richarvey/nginx-php-fpm:latest

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . /var/www/html

# Image configurations
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_CLI=0
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 80
