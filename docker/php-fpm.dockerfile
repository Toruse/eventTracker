FROM php:8.2-fpm

RUN apt-get update && apt-get install -y && docker-php-ext-install pdo pdo_mysql opcache pcntl

WORKDIR /var/www/event-tracker
