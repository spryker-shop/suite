#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ../constants.sh
popd > /dev/null

function sync()
{
    return ${__TRUE}
}

export -f sync
