#!/usr/bin/env bash

cd vendor/spryker/spryker
if [ -z "$(git add -n -A)" ] ; then
    echo "no untracked files in spryker/spryker, all good" ;
else
    git add -n -A
    exit 1
fi
cd -

cd vendor/spryker/spryker-shop
if [ -z "$(git add -n -A)" ] ; then
    echo "no untracked files in spryker/spryker-shop, all good" ;
else
    git add -n -A
    exit 1
fi
cd -

# Avoid untracked files check fail because of Postgres port changing
sudo sed -ie 's/port=5433/port=5432/g' src/Orm/Propel/DE/Config/devtest/propel.json

if [ -z "$(git add src tests -n -A)" ] ; then
    echo "no untracked files in src/tests, all good" ;
else
    git add src tests -n -A
    exit 1
fi

# Avoid untracked files check fail because of Postgres port changing
sudo sed -ie 's/port=5432/port=5433/g' src/Orm/Propel/DE/Config/devtest/propel.json

exit 0
