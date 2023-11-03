#!/usr/bin/env bash

MODULE_LIMIT=10000
MODULE_OFFSET=0

if [ -n "$1" ]; then
    MODULE_LIMIT="$1"
fi

if [ -n "$2" ]; then
    MODULE_OFFSET="$2"
fi

TRANSFER_TOTAL_MODULES_PROCESSED_COUNT=0
ARCHITECTURE_TOTAL_MODULES_PROCESSED_COUNT=0
EXITCODE=0

validateModuleTransfers() {
    modules=$(tests/touched_modules_list.sh $1)
    counter=0

    for module in $modules; do
        if [ $counter -lt $4 ]; then
            let "counter+=1"

            continue
        fi

        let "counter+=1"

        if [ $TRANSFER_TOTAL_MODULES_PROCESSED_COUNT == $3 ]; then
            break
        fi

        let "TRANSFER_TOTAL_MODULES_PROCESSED_COUNT+=1"

        echo $2.$module
        output=$(vendor/bin/spryker-dev-console dev:validate-module-transfers -v -m $2.$module)
        if [ $? -ne 0 ]; then
            echo $output
            EXITCODE=1
        fi
    done
    wait
}

validateModuleArchitecture() {
    modules=$(tests/touched_modules_list.sh $1)
    counter=0

    for module in $modules; do
        if [ $counter -lt $4 ]; then
            let "counter+=1"

            continue
        fi

        let "counter+=1"

        if [ "$ARCHITECTURE_TOTAL_MODULES_PROCESSED_COUNT" == "$3" ]; then
            break
        fi

        let "ARCHITECTURE_TOTAL_MODULES_PROCESSED_COUNT+=1"

        echo $2.$module
        output=$(vendor/bin/console code:sniff:architecture -m $2.$module)
        if [ $? -ne 0 ]; then
            echo $output
            EXITCODE=1
        fi
    done
    wait
}

echo "-----------------------------------------------"
echo "Validating" $MODULE_LIMIT "modules, started from" $MODULE_OFFSET
echo "-----------------------------------------------"

echo "TRANSFERS:"
echo "-----------------------------------------------"
validateModuleTransfers spryker Spryker $MODULE_LIMIT $MODULE_OFFSET
echo "-----------------------------------------------"
validateModuleTransfers spryker-shop SprykerShop $MODULE_LIMIT $MODULE_OFFSET

echo "ARCHITECTURE:"
echo "-----------------------------------------------"
validateModuleArchitecture spryker Spryker $MODULE_LIMIT $MODULE_OFFSET
echo "-----------------------------------------------"
validateModuleArchitecture spryker-shop SprykerShop $MODULE_LIMIT $MODULE_OFFSET

if [ $TRANSFER_TOTAL_MODULES_PROCESSED_COUNT -eq 0 ]; then
    echo 'THERE ARE NO MODULES TO VALIDATE.'
fi

exit $EXITCODE
