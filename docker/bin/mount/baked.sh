#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ../constants.sh
popd > /dev/null

function dataSync()
{
    return ${__TRUE}
}

export -f dataSync
