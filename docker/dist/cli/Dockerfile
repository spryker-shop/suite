# syntax = docker/dockerfile:experimental
FROM spryker_app

ARG SPRYKER_LOG_DIRECTORY=/var/log/spryker

ENV PATH=/data/vendor/bin:$PATH

RUN apk update && apk add \
  nodejs \
  npm \
  netcat-openbsd \
  git

RUN mkdir -p ${SPRYKER_LOG_DIRECTORY}
RUN chown spryker:root ${SPRYKER_LOG_DIRECTORY}
WORKDIR /data
