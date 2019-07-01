#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ../constants.sh
. ../console.sh

../require.sh docker docker-sync
popd > /dev/null

function dataSync()
{
    export DOCKER_SYNC_SKIP_UPDATE=1
    local syncConf="${DEPLOYMENT_PATH}/docker-sync.yml"

    case $1 in
        create)
            verbose "${INFO}Creating 'data-sync' volume${NC}"
            docker volume create --name=${SPRYKER_DOCKER_PREFIX}_data_sync
            ;;

        clean)
            docker-sync clean -c ${syncConf}
            ;;

        stop)
            docker-sync stop -c ${syncConf} -n data-sync
            ;;
        *)
            if [ $(docker ps | grep 5000 | grep ${SPRYKER_DOCKER_PREFIX}_data_sync | wc -l |sed 's/^ *//') -eq 0 ]; then
                verbose "${INFO}Start sync process for data volume${NC}"
                pushd ${PROJECT_DIR} > /dev/null
                docker-sync start -c ${syncConf}
                popd > /dev/null
            fi
            ;;
    esac
}

export -f dataSync
