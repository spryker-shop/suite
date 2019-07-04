# syntax = docker/dockerfile:experimental
ARG SPRYKER_PHP_VERSION=7.2

### Intermediate container - base for composer cache
FROM alpine:3.9 AS composer_cache
RUN mkdir -p /cache && chmod 0777 /cache

# Intermediate container - base for app, with composer deps
FROM spryker/php:${SPRYKER_PHP_VERSION} AS spryker_app_dependencies

# Create log directory
ARG SPRYKER_LOG_DIRECTORY
ENV SPRYKER_LOG_DIRECTORY=${SPRYKER_LOG_DIRECTORY}
RUN mkdir -p ${SPRYKER_LOG_DIRECTORY} && \
    chown spryker:spryker ${SPRYKER_LOG_DIRECTORY}

ARG SKIP_BUILD_IF_PERFORMED
ARG USER_UID
RUN usermod -u ${USER_UID} spryker

WORKDIR /data
USER spryker

# Install composer modules for Spryker
COPY --chown=spryker:spryker composer.json composer.lock vendor ${srcRoot}/
ARG GITHUB_TOKEN
RUN --mount=type=cache,target=/home/spryker/.composer/cache,from=composer_cache,source=/cache \
  bash -c 'if [ ! -z $GITHUB_TOKEN ]; then export COMPOSER_AUTH="{\"github-oauth\": {\"github.com\": \"${GITHUB_TOKEN}\"}}"; fi; \
    if [ ! -f ${srcRoot}/data/vendor/bin/install -o "x${SKIP_BUILD_IF_PERFORMED}" = "x0" ] ; then composer install --no-interaction --optimize-autoloader ; fi'

### Intermediate container - with dependencies, PHP code and configs
FROM spryker_app_dependencies AS spryker_app_base
COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker *.php ${srcRoot}/
RUN chmod 600 ${srcRoot}/config/Zed/*.key

ARG SKIP_DUMP_AUTOLOAD=0
RUN if [ "x${SKIP_DUMP_AUTOLOAD}" = "x0" ] ; then composer dump-autoload -o ; fi

ARG APPLICATION_ENV
ARG SPRYKER_DB_ENGINE
ENV APPLICATION_ENV=${APPLICATION_ENV}
ENV SPRYKER_DB_ENGINE=${SPRYKER_DB_ENGINE}

RUN if [ ! -f ${srcRoot}/data/src/Generated -o "x${SKIP_BUILD_IF_PERFORMED}" = "x0" ] ; then vendor/bin/install -r docker -s build ; fi

# Tests contain transfer declaration
COPY --chown=spryker:spryker tests ${srcRoot}/tests

ENV DEVELOPMENT_CONSOLE_COMMANDS=1
RUN if [ ! -f ${srcRoot}/data/src/Generated -o "x${SKIP_BUILD_IF_PERFORMED}" = "x0" ] ; then vendor/bin/install -r docker -s build-development ; fi

### Final container, with testing tools, public directory and data
FROM spryker_app_base AS spryker_app

# Add remaining Spryker files/directories
COPY --chown=spryker:spryker data ${srcRoot}/data
COPY --chown=spryker:spryker public ${srcRoot}/public
COPY --chown=spryker:spryker .* *.* LICENSE ${srcRoot}/

RUN if [ "x${SKIP_DUMP_AUTOLOAD}" = "x0" ] ; then composer dump-autoload -o ; fi

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
CMD [ "php-fpm", "--nodaemonize" ]
EXPOSE 9000
