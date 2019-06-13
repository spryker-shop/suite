# syntax = docker/dockerfile:experimental

ARG SPRYKER_PHP_VERSION=7.2

# Intermediate container - base for composer cache
FROM alpine:3.9 AS composer_cache
RUN mkdir -p /cache && chmod 0777 /cache

# Intermediate container - composer deps
FROM spryker/php:${SPRYKER_PHP_VERSION} AS spryker_app_dependencies

USER spryker

WORKDIR /data

COPY --chown=spryker:spryker composer.json composer.lock ${srcRoot}/

ARG GITHUB_TOKEN

# Install all modules for Spryker
RUN --mount=type=cache,target=/home/spryker/.composer/cache,from=composer_cache,source=/cache \
  bash -c 'if [ ! -z $GITHUB_TOKEN ]; then export COMPOSER_AUTH="{\"github-oauth\": {\"github.com\": \"${GITHUB_TOKEN}\"}}"; fi; \
    composer install --no-interaction --optimize-autoloader'

# Intermediate container - with dependencies, PHP code and configs
FROM spryker_app_dependencies AS spryker_app_base
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker *.php ${srcRoot}/

ARG APPLICATION_ENV
ARG SPRYKER_DB_ENGINE
ENV APPLICATION_ENV=${APPLICATION_ENV}
ENV SPRYKER_DB_ENGINE=${SPRYKER_DB_ENGINE}

RUN vendor/bin/install -r docker -s build

USER root

# Final container, with testing tools, public directory and data
FROM spryker_app_base AS spryker_app

ARG SPRYKER_LOG_DIRECTORY

COPY --chown=spryker:spryker data ${srcRoot}/data
COPY --chown=spryker:spryker public ${srcRoot}/public
COPY --chown=spryker:spryker tests ${srcRoot}/tests
COPY --chown=spryker:spryker .* *.* LICENSE ${srcRoot}/

#Create log directory
RUN mkdir -p ${SPRYKER_LOG_DIRECTORY}
RUN chown spryker:spryker ${SPRYKER_LOG_DIRECTORY}
CMD [ "php-fpm", "--nodaemonize" ]
EXPOSE 9000
