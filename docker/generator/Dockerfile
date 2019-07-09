# syntax = docker/dockerfile:experimental
ARG SPRYKER_PHP_VERSION=7.3

### Intermediate container - base for composer cache
FROM alpine:3.9 AS composer_cache
RUN mkdir -p /cache && chmod 0777 /cache

# Intermediate container - base for app, with composer deps
FROM spryker/php:${SPRYKER_PHP_VERSION} AS spryker_app_dependencies

RUN apk add --no-cache openssl

WORKDIR /data
USER spryker

# Install composer modules for Spryker
COPY --chown=spryker:spryker composer.json composer.lock ${srcRoot}/
RUN --mount=type=cache,target=/home/spryker/.composer/cache,from=composer_cache,source=/cache \
    composer install --no-interaction --optimize-autoloader --no-dev

### Intermediate container - with dependencies, PHP code and configs
FROM spryker_app_dependencies AS spryker_docker_sdk
COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker openssl ${srcRoot}/openssl
COPY --chown=spryker:spryker index.php ${srcRoot}

RUN chmod 755 ${srcRoot}/openssl/generate.sh

CMD [ "php", "index.php" ]
