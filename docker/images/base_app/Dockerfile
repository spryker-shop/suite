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

WORKDIR /data
USER spryker

# Install composer modules for Spryker
# TODO move to platform
ENV COMPOSE_HTTP_TIMEOUT=400
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

ARG APPLICATION_ENV
ARG SPRYKER_DB_ENGINE
ENV APPLICATION_ENV=${APPLICATION_ENV}
ENV SPRYKER_DB_ENGINE=${SPRYKER_DB_ENGINE}

RUN vendor/bin/install -r docker -s build \
    && rm -rf /var/run/opcache/*

### Final container, with testing tools, public directory and data
FROM spryker_app_base AS spryker_app

# Add remaining Spryker files/directories
COPY --chown=spryker:spryker data ${srcRoot}/data
COPY --chown=spryker:spryker public ${srcRoot}/public
COPY --chown=spryker:spryker tests ${srcRoot}/tests
COPY --chown=spryker:spryker .* *.* LICENSE ${srcRoot}/

ARG DEPLOYMENT_PATH
ENV DEPLOYMENT_PATH=${DEPLOYMENT_PATH}

# Allow project-level customisations for PHP
COPY ${DEPLOYMENT_PATH}/context/php/php-fpm.d/worker.conf /usr/local/etc/php-fpm.d/worker.conf
COPY ${DEPLOYMENT_PATH}/context/php/php.ini /usr/local/etc/php/
COPY ${DEPLOYMENT_PATH}/context/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/
COPY ${DEPLOYMENT_PATH}/context/jenkins/jenkins.job.xml.twig ${srcRoot}/config/Zed/cronjobs/jenkins.docker.xml.twig

## Prepare entrypoint
#COPY ${DEPLOYMENT_PATH}/context/php/entrypoint.sh /spryker-entrypoint.sh
#RUN chmod 755 /spryker-entrypoint.sh
#ENTRYPOINT [ "/spryker-entrypoint.sh" ]

# Run FPM as default command
USER root
CMD [ "php-fpm", "--nodaemonize" ]
EXPOSE 9000
