# syntax = docker/dockerfile:experimental
ARG SPRYKER_PHP_VERSION=7.2
FROM spryker/php:${SPRYKER_PHP_VERSION}

ARG SPRYKER_LOG_DIRECTORY
ARG USER_UID
ARG USER_GID
ARG APPLICATION_ENV
ARG SPRYKER_DB_ENGINE
ENV APPLICATION_ENV=${APPLICATION_ENV}
ENV SPRYKER_DB_ENGINE=${SPRYKER_DB_ENGINE}

ENV PATH=/data/vendor/bin:$PATH

RUN usermod -u ${USER_UID} spryker

RUN mkdir -p ${SPRYKER_LOG_DIRECTORY}
RUN chown -R spryker:spryker /data
RUN chown -R spryker:spryker /home/spryker
RUN chown spryker:spryker ${SPRYKER_LOG_DIRECTORY}
CMD [ "php-fpm", "--nodaemonize" ]
EXPOSE 9000
