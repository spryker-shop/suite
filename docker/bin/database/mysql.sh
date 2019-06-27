#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ../constants.sh
popd > /dev/null

function doesDatabaseHaveTables()
{
    tableCount=$(execSpryker 'export VERBOSE=0; mysql -h ${SPRYKER_DB_HOST} -u ${SPRYKER_DB_USERNAME} -p${SPRYKER_DB_PASSWORD} -e "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = '\''${SPRYKER_DB_DATABASE}'\''"'|wc -l| sed 's/^ *//')

    [ "$tableCount" -gt 0 ] && return ${__TRUE} || return ${__FALSE}
}

export -f doesDatabaseHaveTables
