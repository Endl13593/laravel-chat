FROM jpaulolxm/php-7.4-fpm:1.3

WORKDIR /var/www/html

RUN mkdir /var/log/supervisor

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]

