FROM php:8.4-fpm-alpine3.20

RUN apk update && apk add \
curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

CMD ["php-fpm"]