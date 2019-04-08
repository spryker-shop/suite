FROM php:7-fpm-alpine

RUN apk add --no-cache --update --virtual buildDeps autoconf
RUN apk add --update --no-cache alpine-sdk postgresql-dev postgresql-client libpng libpng-dev \
        libsodium-dev icu-dev gmp-dev npm zip git httpie nmap && \
        ulimit -n 65535 && \
        rm -rf /var/cache/apk/*

RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install gd pdo_pgsql pgsql bcmath gmp intl sodium sockets

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/html/data/DE/logs /var/www/html/data/AT/logs /var/www/html/data/US/logs /var/www/html/src/Generated /var/www/html/vendor

RUN chown -R www-data:www-data /var/www/html/data/DE
RUN chown -R www-data:www-data /var/www/html/data/AT
RUN chown -R www-data:www-data /var/www/html/data/US
RUN chown -R www-data:www-data /var/www/html/vendor

RUN chmod -R 777 /var/www/html/data/DE /var/www/html/data/AT /var/www/html/data/US /var/www/html/src/Generated /var/www/html/vendor || true

#
# limited user
#
USER www-data

WORKDIR /var/www/html

ADD $PWD /var/www/html

RUN composer global require hirak/prestissimo
RUN composer install --no-interaction

#RUN vendor/bin/install # needs db rabbit redis etc running at same time to work