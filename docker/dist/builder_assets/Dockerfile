# syntax = docker/dockerfile:experimental

# Comment #1: to minimize dependencies, we're running frontend commands in a container,
#   which inherits from spryker_platform. It does not yet include php code.
#   For this reason, commands which are executed by "build-frontend" section of installer
#   were extracted and copied here.
#
# Comment #2: we use bind-mount here from spryker_app container. Allows access to /data/vendor
#   without actually copying the files to frontend builder containers. As frontend depends
#   on vendor/, public/, frontend/, tsconfig.json and packages.json/packages.lock, we don't need
#   to pass full spryker code as context. This will allow to build frontend and PHP code in parallel
#   by buildkit, when we combine Dockerfiles
#
# Comment #3: we should actually mount from container spryker_app_dependencies, in order to avoid
#   building frontend in someting in src/,config/,public/,tests/ changes. We don't have access to
#   spryker_app_dependencies as it's an intermediate container from another Dockerfile, that would
#   require merging the dockerfiles.

ARG SPRYKER_PHP_VERSION=7.2

# Intermediate container - project dependencies
FROM spryker/php:${SPRYKER_PHP_VERSION} AS spryker_node_dependencies
RUN apk update && apk add \
  nodejs \
  npm \
  git

COPY package.json package-lock.json ${srcRoot}/
# See comment #1, extracted from installer/build-static/dependencies-install
RUN --mount=type=cache,target=/root/.npm \
    npm install

# Intermediate container - frontend build
FROM spryker_node_dependencies AS builder_assets
COPY frontend ${srcRoot}/frontend
COPY tsconfig.json ${srcRoot}/
RUN mkdir -p /assets && mkdir -p public/Zed && mkdir -p public/Yves && ln -s /assets public/Zed/assets && ln -s /assets public/Yves/assets

# See comment #1, extracted from installer/build-static/{yves-install-dependencies,zed-install-dependencies,yves-build-frontend,zed-build-frontend}
# See comment #2
# See comment #3
RUN --mount=type=cache,target=/root/.npm \
    --mount=type=bind,from=spryker_app,source=/data/vendor,target=/data/vendor,readwrite \
    --mount=type=bind,from=spryker_app,source=/data/src,target=/data/src,readwrite \
    find vendor -name package.json | while read file; do ( \
        cd $(dirname $file); \
        npm install \
    ); done && \
    npm run yves && \
    npm run zed && \
    chown -R spryker:spryker /assets

VOLUME [ /assets ]
