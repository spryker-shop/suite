echo $(git -C vendor/spryker/$1 diff --name-only --diff-filter=ACMRTUXB master... | grep "^Bundles\/" | cut -d "/" -f2- | cut -d "/" -f1 | sort | uniq)
