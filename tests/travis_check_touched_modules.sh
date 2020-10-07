#!/usr/bin/env bash

EXITCODE=0

validateModuleTransfers() {
  MODULES=$(git -C vendor/spryker/$1 diff --name-only --diff-filter=ACMRTUXB master... | grep "^Bundles\/" | cut -d "/" -f2- | cut -d "/" -f1 | sort | uniq)

  for module in $MODULES
      do
          output=`vendor/bin/spryker-dev-console dev:validate-module-transfers -v -m $2.$module`
          if [ $? -ne 0 ]; then
              echo $output
              EXITCODE=1
          fi
      done
  wait
}

validateModuleTransfers spryker Spryker
validateModuleTransfers spryker-shop  SprykerShop

exit $EXITCODE
