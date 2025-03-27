FROM php:8.2-cli

RUN apt-get update && apt-get install -y supervisor
RUN docker-php-ext-install pdo pdo_mysql
RUN mkdir -p "/var/log/supervisor"

COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/conf.d/supervisord.conf"]