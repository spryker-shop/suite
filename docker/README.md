# Spryker Commerce OS - Docker SDK
[![Build Status](https://travis-ci.org/spryker/docker-sdk.svg)](https://travis-ci.org/spryker/docker-sdk)

## Description


## Installation

> Note: All command should be run from Spryker project root directory.  
 
Run the following to fetch Docker SDK tools.
```bash
git clone https://github.com/spryker/docker-sdk.git ./docker
```

> Note: Make sure `docker 18.09.1+` and `docker-compose 1.23+` are installed in the local environment.

### Preparation

1. Prepare `deploy.yml` file according the documentation.
    * Examples can be found in [Spryker Shop Suite](https://github.com/spryker-shop/suite) by mask `deploy*.yml`.
1. Prepare configuration based on environment name defined in `deploy.yml#environment`.
    * Examples for `docker` environment name can be found in [Spryker Shop Suite](https://github.com/spryker-shop/suite/tree/master/config/Shared)  by mask `config_default-docker*.php`.
1. Prepare installation `docker.yml` file.
    * Example can be found in [Spryker Shop Suite](https://github.com/spryker-shop/suite/tree/master/config/install/docker.yml).
1. Prepare `.dockerignore` to match the project infrastructure.
    * Example can be found in [Spryker Shop Suite](https://github.com/spryker-shop/suite/tree/master/.dockerignore).

## Quick start

Initialize the docker setup by running the following.

```bash
docker/sdk bootstrap
```

Run the `up` command and wait until done.
```
docker/sdk up
```

> Note: Make sure all domains from `deploy.yml` are defined as `127.0.0.1` in `hosts` on the local environment.

Use domains defined in `deploy.yml` to access the application.

## Documentation

[Spryker Documentation](https://documentation.spryker.com/installation/docker-sdk/docker-sdk-201907.htm)
