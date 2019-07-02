#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ./constants.sh
. ./console.sh

VERBOSE=0 ./require.sh docker tr
popd > /dev/null

# ------------------
function checkDockerVersion()
{
    local requiredMinimalVersion=${1:-'18.09.1'}
    # TODO check if docker is not running
    local installedVersion=$(which docker > /dev/null; test $? -eq 0 && docker version --format '{{.Server.Version}}' || echo 0;)

    verbose -n "${INFO}Checking docker version...${NC}"

    if [ $(echo "${installedVersion}" | tr -d '.') -lt $(echo "${requiredMinimalVersion}" | tr -d '.') ]
    then
        verbose ""
        error "${WARN}Docker version ${installedVersion} is not supported. Please update docker to at least ${requiredMinimalVersion}.${NC}"
        exit 1
    fi

    verbose "[OK]"
}

checkDockerVersion $@
