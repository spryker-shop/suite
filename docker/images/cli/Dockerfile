# syntax = docker/dockerfile:experimental
ARG SPRYKER_DOCKER_TAG
ARG SPRYKER_DOCKER_PREFIX

FROM ${SPRYKER_DOCKER_PREFIX}_app:${SPRYKER_DOCKER_TAG}

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
