FROM php:8.2-fpm-alpine


# Install pdo_mysql
RUN docker-php-ext-install pdo_mysql

