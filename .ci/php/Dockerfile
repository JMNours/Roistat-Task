FROM php:8.4-fpm-alpine3.20

RUN apk update && apk add \
curl

WORKDIR /var/www/html

CMD ["php-fpm"]