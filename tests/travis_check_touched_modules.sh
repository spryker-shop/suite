#!/usr/bin/env bash

EXITCODE=0

validateModuleTransfers() {
  MODULES=$(git -C vendor/spryker/$1 diff --name-only --diff-filter=ACMRTUXB master... | grep "^Bundles\/" | cut -d "/" -f2- | cut -d "/" -f1 | sort | uniq)

  echo "transfers"
  for module in $MODULES
      do
          echo $2.$module
          output=`vendor/bin/spryker-dev-console dev:validate-module-transfers -v -m $2.$module`
          if [ $? -ne 0 ]; then
              echo $output
              EXITCODE=1
          fi
      done
  wait
}

validateModuleArchitecture() {
  MODULES=$(git -C vendor/spryker/$1 diff --name-only --diff-filter=ACMRTUXB master... | grep "^Bundles\/" | cut -d "/" -f2- | cut -d "/" -f1 | sort | uniq)

  echo "architecture"
  for module in $MODULES
      do
          echo $2.$module
          output=`vendor/bin/console code:sniff:architecture -m $2.$module`
          if [ $? -ne 0 ]; then
              echo $output
              EXITCODE=1
          fi
      done
  wait
}

validateModuleTransfers spryker Spryker
validateModuleTransfers spryker-shop  SprykerShop

validateModuleArchitecture spryker Spryker
validateModuleArchitecture spryker-shop  SprykerShop

exit $EXITCODE
