# syntax = docker/dockerfile:experimental
ARG SPRYKER_PLATFORM_IMAGE=spryker/php:7.2
FROM ${SPRYKER_PLATFORM_IMAGE}

ARG SPRYKER_LOG_DIRECTORY
ARG USER_UID
ARG USER_GID
ARG APPLICATION_ENV
ARG SPRYKER_DB_ENGINE
ARG DEPLOYMENT_PATH
ENV SPRYKER_DB_ENGINE=${SPRYKER_DB_ENGINE}
ENV DEVELOPMENT_CONSOLE_COMMANDS=1

ENV PATH=/data/vendor/bin:$PATH

RUN usermod -u ${USER_UID} spryker

RUN mkdir -p ${SPRYKER_LOG_DIRECTORY}
RUN chown -R spryker:spryker /data
RUN chown -R spryker:spryker /home/spryker
RUN chown spryker:spryker ${SPRYKER_LOG_DIRECTORY}
CMD [ "php-fpm", "--nodaemonize" ]
EXPOSE 9000
