#!/bin/bash

git checkout master && git pull
cd vendor/spryker/spryker
git checkout master && git pull
cd ../spryker-shop
git checkout master && git pull
cd ../../../
COMPOSER_MEMORY_LIMIT=-1 composer install -o --ignore-platform-reqs
echo "Transfers generation..."
vendor/bin/console transfer:generate
