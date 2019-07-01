#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ../constants.sh
. ../console.sh

../require.sh docker docker-sync
popd > /dev/null

function dataSync()
{
#    local syncConf="${DEPLOYMENT_PATH}/docker-sync.yml"

    case $1 in
        create)
            verbose "${INFO}Creating 'data-sync' volume${NC}"
            docker volume create --name=${SPRYKER_DOCKER_PREFIX}_data_sync
            docker stop ${SPRYKER_DOCKER_PREFIX}_data_sync || true
            docker rm ${SPRYKER_DOCKER_PREFIX}_data_sync || true
            docker create --name ${SPRYKER_DOCKER_PREFIX}_data_sync \
                -v ${SPRYKER_DOCKER_PREFIX}_data_sync:/data \
                 ${SPRYKER_DOCKER_PREFIX}_app:${SPRYKER_DOCKER_TAG}
            ;;

        clean)
#            mutagen terminate SESSION_ID
            mutagen daemon stop
            docker stop ${SPRYKER_DOCKER_PREFIX}_data_sync
            docker rm ${SPRYKER_DOCKER_PREFIX}_data_sync
            docker volume rm ${SPRYKER_DOCKER_PREFIX}_data_sync
            ;;

        stop)
#            mutagen terminate SESSION_ID
            ;;
        *)
            if [ $(docker ps | grep 5000 | grep ${SPRYKER_DOCKER_PREFIX}_data_sync | wc -l |sed 's/^ *//') -eq 0 ]; then
                mutagen daemon start
                docker start ${SPRYKER_DOCKER_PREFIX}_data_sync
                mutagen create ${PROJECT_DIR} docker://${SPRYKER_DOCKER_PREFIX}_data_sync/data --default-owner-beta=spryker --default-group-beta=spryker
                # TODO save mutagen session id
            fi
            ;;
    esac
}

export -f dataSync
