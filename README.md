# Spryker Suite
[![Build Status](https://github.com/spryker-shop/suite/workflows/CI/badge.svg)](https://github.com/spryker-shop/suite/actions?query=workflow%3ACI)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)

Suite (internal)
[![Build Status](https://travis-ci.com/spryker/suite-nonsplit.svg?token=7jVDNZFJxpvBrFetYhbF&branch=master)](https://travis-ci.com/spryker/suite-nonsplit)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spryker/suite-nonsplit/badges/quality-score.png?b=master&s=bd1f64c27e30a53590063d808335d7957af612d0)](https://scrutinizer-ci.com/g/spryker/suite-nonsplit/?branch=master)

Core (internal)
[![Build Status](https://travis-ci.com/spryker/spryker.svg?token=7jVDNZFJxpvBrFetYhbF&branch=master)](https://travis-ci.com/spryker/spryker)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spryker/spryker/badges/quality-score.png?b=master&s=25d80f2c1a93b3ae4d907ea8e75800a87469f088)](https://scrutinizer-ci.com/g/spryker/spryker/?branch=master)

Shop (internal)
[![Build Status](https://travis-ci.com/spryker/spryker-shop.svg?token=7jVDNZFJxpvBrFetYhbF&branch=master)](https://travis-ci.com/spryker/spryker-shop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spryker/spryker-shop/badges/quality-score.png?b=master&s=714e779da63d6a6fc0c8844cbfb252c66e286b96)](https://scrutinizer-ci.com/g/spryker/spryker-shop/?branch=master)

License: [MIT](LICENSE)

## Installation

For DevVM based installation instructions, see [About the Installation Guides](https://documentation.spryker.com/docs/about-installation).

If you encounter any issues during or after the installation, see [Troubleshooting article](https://documentation.spryker.com/docs/troubleshooting).

## Docker installation

For detailed installation instructions of Spryker in Docker, see [Getting Started with Docker](https://documentation.spryker.com/docs/getting-started-with-docker).

For troubleshooting of Docker based instanaces, see [Troubleshooting](https://documentation.spryker.com/docs/spryker-in-docker-troubleshooting).

### Prerequisites

For the installation prerequisites, see [Docker Installation Prerequisites](https://documentation.spryker.com/docs/docker-installation-prerequisites).

Recommended system requirements for MacOS:

|Macbook type|vCPU| RAM|
|---|---|---|
|15' | 4 | 6GB |
|13' | 2 | 4GB |

### Installation

Run the command:
```bash
git submodule update --init --force docker
```

### Developer environment

1. Run the command right after cloning the repository:

```bash
docker/sdk boot deploy.dev.yml
docker/sdk up
```

2. Git checkout:

```bash
git checkout your_branch
git submodule update --init --force docker && docker/sdk boot -s deploy.dev.yml

docker/sdk up --build --assets --data
```
> Optional `up` command arguments:
>
> - `--build` - update composer, generate transfer objects, etc.
> - `--assets` - build assets
> - `--data` - get new demo data

3. If you get unexpected application behavior or unexpected errors:

    1. Run the command:
    ```bash
    git status
    ```

    2. If there are unnecessary untracked files (red ones), remove them.

    3. Restrart file sync and re-build the codebase:
    ```bash
    docker/sdk trouble
    rm -rf ./docker && git submodule update --init --force docker && docker/sdk boot -s deploy.dev.yml

    docker/sdk up --build --assets
    ```

4. If you do not see the expected demo data on the Storefront:

    1. Check the queue broker and wait until all queues are empty.

    2. If the queue is empty but the issue persists, reload the demo data:
    ```bash
    docker/sdk trouble
    rm -rf ./docker && git submodule update --init --force docker && docker/sdk boot -s deploy.dev.yml

    docker/sdk up --build --assets --data
    ```

### Production-like environment

1. Run the following command right after cloning the repository:

```bash
docker/sdk boot -s
docker/sdk up
```

2. Git checkout with assets and importing data:

```bash
git checkout your_branch
git submodule update --init --force docker && docker/sdk boot -s

docker/sdk up --assets --data
```

> Optional `up` command arguments:
>
> - `--assets` - build assets
> - `--data` - get new demo data

3. Light git checkout:

```bash
git checkout your_branch
git submodule update --init --force docker && docker/sdk boot -s

docker/sdk up
```

4. Reload all the data:

```bash
docker/sdk clean-data && docker/sdk up && docker/sdk console q:w:s -v -s
```

### Troubleshooting

**No data on Storefront**

Use the following services to check the status of queues and jobs:
- queue.spryker.local
- scheduler.spryker.local

**Fail whale**

1. Run the command:
```bash
docker/sdk logs
```
2. Add several returns to mark the line you started from.
3. Open the page with the error.
4. Check the logs.

**MacOS and Windows - files synchronization issues in Development mode**

1. Follow sync logs:
```bash
docker/sdk sync logs
```
2. Hard reset:
```bash
docker/sdk trouble && rm -rf vendor && rm -rf src/Generated && docker/sdk sync && docker/sdk up
```

**Errors**

`ERROR: remove spryker_logs: volume is in use - [{container_hash}]`

1. Run the command:
```bash
docker rm -f {container_hash}
```
2. Repeat the failed command.

`Error response from daemon: OCI runtime create failed: .... \\\"no such file or directory\\\"\"": unknown.`

Repeat the failed command.
