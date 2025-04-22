FROM php:8.2-fpm

RUN apt-get update && apt-get install -y libicu-dev && docker-php-ext-install pdo pdo_mysql opcache pcntl intl

WORKDIR /var/www/event-tracker
