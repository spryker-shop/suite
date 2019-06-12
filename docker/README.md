# Spryker OS - Docker SDK

## Bootstrapping
```
# Clone Spryker Docker SDK distribution:
git clone --single-branch --branch v201906 spryker/docker ./docker/dist

# Bootstrap local docker setup
docker/dist/sdk bootstrap
```

## Quick start
```
docker/sdk up
```

## Under the hood
### Bootstrapping and project-level overrides
Original docker SDK distribution is cloned into `docker/dist`. When `docker/dist/sdk bootstrap` is executed, it copies all files from `docker/dist` to `docker/`, then it copies project-lever override files from `docker/local` into `docker/`. If you would like to change some settings, for example - customize the `.env` file - just place your version of the file in `docker/local` directory and re-run `docker/dist/sdk bootstrap`.

### Contents of docker/dist
You can either copy the contests of `spryker/docker` repository into `docker/dist` (as the example above does) or use `git submodule` to setup tracking of remote repository. If you would like to overwrite any file from the original repository, please use project-lever overrides.
