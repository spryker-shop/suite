# syntax = docker/dockerfile:experimental
ARG SPRYKER_PLATFORM_IMAGE=spryker/php:7.2

### Intermediate container - base for composer cache
FROM alpine:3.9 AS composer_cache
RUN mkdir -p /cache && chmod 0777 /cache

# Intermediate container - base for app, with composer deps
FROM ${SPRYKER_PLATFORM_IMAGE} AS spryker_app_dependencies

# Create log directory
ARG SPRYKER_LOG_DIRECTORY
ENV SPRYKER_LOG_DIRECTORY=${SPRYKER_LOG_DIRECTORY}
RUN mkdir -p ${SPRYKER_LOG_DIRECTORY} && \
    chown spryker:spryker ${SPRYKER_LOG_DIRECTORY}

WORKDIR /data
USER spryker

# Install composer modules for Spryker
COPY --chown=spryker:spryker composer.json composer.lock ${srcRoot}/
ARG GITHUB_TOKEN
RUN --mount=type=cache,target=/home/spryker/.composer/cache,from=composer_cache,source=/cache \
  bash -c 'if [ ! -z $GITHUB_TOKEN ]; then export COMPOSER_AUTH="{\"github-oauth\": {\"github.com\": \"${GITHUB_TOKEN}\"}}"; fi; \
    composer install --no-interaction --optimize-autoloader'

### Intermediate container - with dependencies, PHP code and configs
FROM spryker_app_dependencies AS spryker_app_base
COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker *.php ${srcRoot}/
RUN chmod 600 ${srcRoot}/config/Zed/*.key

RUN composer dump-autoload -o

ARG APPLICATION_ENV
ARG SPRYKER_DB_ENGINE
ENV APPLICATION_ENV=${APPLICATION_ENV}
ENV SPRYKER_DB_ENGINE=${SPRYKER_DB_ENGINE}

RUN vendor/bin/install -r docker -s build

# Tests contain transfer declaration
COPY --chown=spryker:spryker tests ${srcRoot}/tests

ENV DEVELOPMENT_CONSOLE_COMMANDS=1
RUN vendor/bin/install -r docker -s build-development

### Final container, with testing tools, public directory and data
FROM spryker_app_base AS spryker_app

# Add remaining Spryker files/directories
COPY --chown=spryker:spryker data ${srcRoot}/data
COPY --chown=spryker:spryker public ${srcRoot}/public
COPY --chown=spryker:spryker frontend ${srcRoot}/frontend
COPY --chown=spryker:spryker .* *.* LICENSE ${srcRoot}/

RUN composer dump-autoload -o

RUN rm -rf /var/run/opcache/*

ARG DEPLOYMENT_PATH
ENV DEPLOYMENT_PATH=${DEPLOYMENT_PATH}

# PHP configuration
COPY ${DEPLOYMENT_PATH}/context/php/php-fpm.d/worker.conf /usr/local/etc/php-fpm.d/worker.conf
COPY ${DEPLOYMENT_PATH}/context/php/php.ini /usr/local/etc/php/
# TODO use opcache before composer somehow
COPY ${DEPLOYMENT_PATH}/context/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/

# Run FPM as default command
USER root
RUN chown -R spryker:spryker /home/spryker

CMD [ "php-fpm", "--nodaemonize" ]
EXPOSE 9000
