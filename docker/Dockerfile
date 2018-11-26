FROM php:7-fpm-alpine

RUN apk add --no-cache --update --virtual buildDeps autoconf
RUN apk add --update --no-cache alpine-sdk postgresql-dev postgresql-client libpng libpng-dev \
        libsodium-dev icu-dev gmp-dev npm zip git httpie nmap && \
        ulimit -n 65535 && \
        rm -rf /var/cache/apk/*

RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install gd pdo_pgsql pgsql bcmath gmp intl sodium

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER www-data

WORKDIR /var/www/html

ADD $PWD /var/www/html

RUN chmod -R 755 /var/www/html
RUN chown -R www-data:www-data /var/www/html

RUN composer global require hirak/prestissimo
RUN composer install --no-interaction

#RUN vendor/bin/install