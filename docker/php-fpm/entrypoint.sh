#!/bin/sh

chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

php /var/www/html/artisan config:cache

php-fpm
