FROM php:7-fpm-alpine

ADD $PWD /var/www/html

# because crikey php eats memory
ENV COMPOSER_MEMORY_LIMIT=-1

RUN apk add --no-cache --update --virtual buildDeps autoconf
RUN apk add --update --no-cache alpine-sdk postgresql-dev libpng libpng-dev \
        libsodium-dev icu-dev gmp-dev zip git && \
        ulimit -n 65535 && \
        rm -rf /var/cache/apk/*

RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install gd pdo_pgsql pgsql bcmath gmp intl sodium

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer global require hirak/prestissimo
RUN composer install --no-interaction
#RUN vendor/bin/install