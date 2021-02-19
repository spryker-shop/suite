#!/bin/sh

delete_branches () {
  git checkout master
  git fetch
  git remote prune origin
  pwd
  git branch -vv | grep 'origin/.*: исчез]' | awk '{print $1}' | xargs git branch -D
}

delete_branches
cd vendor/spryker/spryker
delete_branches
cd ../spryker-shop
delete_branches
cd ../../../
