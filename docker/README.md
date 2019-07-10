# Spryker OS - Docker SDK

## Bootstrapping
```
# Clone Spryker Docker SDK distribution:
git clone --single-branch --branch v201906 https://github.com/spryker/docker.git ./docker/dist

# Bootstrap local docker setup
docker/dist/sdk bootstrap
```

## Quick start
```
docker/sdk up
```

## Under the hood
### Bootstrapping and project-level overrides
Bootstrapping process, started with `docker/dist/sdk bootstrap`, will compose files in directories in `docker/` directory, based on Spryker Docker SDK (`docker/dist`) and project-level configuration overrides (`docker/local`).

Original Spryker Docker SDK distribution is located in `docker/dist`. When `docker/dist/sdk bootstrap` is executed, it copies all files from `docker/dist` to `docker/`, then it copies project-level override files from `docker/local` into `docker/`. If you would like to change some settings on a project level, so that your customization is available to your team and part of your repository (for example, customize the `.env` file or multi-store setup) - just place your version of file(s) in `docker/local` directory and re-run `docker/dist/sdk bootstrap`.

### Individual customization
If you would like to customise only your local environment and not commit those customizations to your repository, for individual development changes - then you can edit files directly in `docker/` directory. Note that your local changes will be lost if you run `docker/dist/sdk bootstrap` again. Those files are added to `.gitignore`, so changes will stay on your workstation only and will not be commited.

### Contents of docker/dist
Directory `docker/dist` contains original distribution of Spryker Docker SDK. You can either copy contents of `spryker/docker` repository into `docker/dist` (as the example above suggests) or use `git submodule` to setup tracking of remote repository. If you would like to overwrite any file from the original repository, please use project-level overrides, as it will be easier for you to get updates of SDK in the future.
