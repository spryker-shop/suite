#!/bin/sh

git checkout master && git pull
cd vendor/spryker/spryker
git checkout master && git pull
cd ../spryker-shop
git checkout master && git pull
cd ../../../
composer install --ignore-platform-reqs
echo "Transfers generation"
vendor/bin/console transfer:generate
composer dump -o
