#!/bin/sh

git_gc(){
    git gc --aggressive --prune
    cd vendor/spryker/spryker
    git gc --aggressive --prune
    cd ../spryker-shop
    git gc --aggressive --prune
    cd ../../../
}

docker_clean(){
    docker system prune -a --volumes
    docker/sdk boot devploy.dev.mariadb.yml
    docker/sdk up
}

read -p "Do you wish to run docker prune (y/N)?" yn
case $yn in
    [Yy]* ) git_gc; docker_clean; break;;
    [Nn]* ) git_gc; break;;
    * ) git_gc; exit;;
esac
