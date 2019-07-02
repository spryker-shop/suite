#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ../constants.sh
popd > /dev/null

function doesDatabaseHaveTables()
{
    tableCount=$(execSpryker 'VERBOSE=0 PGPASSWORD="${SPRYKER_DB_ROOT_PASSWORD}" psql -lqt -h ${SPRYKER_DB_HOST} -U ${SPRYKER_DB_USERNAME} | grep ${SPRYKER_DB_DATABASE} > /dev/null 2>&1 && psql -h ${SPRYKER_DB_HOST} -U ${SPRYKER_DB_USERNAME} ${SPRYKER_DB_DATABASE} -c "SELECT count(*) FROM information_schema.tables WHERE table_catalog = "${SPRYKER_DB_DATABASE}";" -t 2>&1 | sed -e "s/^[:space:]*//" || echo 0 ')

    [ "$tableCount" -gt 0 ] && return ${__TRUE} || return ${__FALSE}
}

function initDatabase()
{
    execSprykerMultiString <<'EOF'
        export VERBOSE=0
        export PGPASSWORD="${SPRYKER_DB_ROOT_PASSWORD}"
        psql -h ${SPRYKER_DB_HOST} -U ${SPRYKER_DB_ROOT_USERNAME} -tc "SELECT COUNT(*) FROM pg_catalog.pg_roles WHERE rolname = '${SPRYKER_DB_USERNAME}'" | grep -q 1 \
            || psql -h ${SPRYKER_DB_HOST} -U ${SPRYKER_DB_ROOT_USERNAME} -c "CREATE ROLE ${SPRYKER_DB_USERNAME} LOGIN PASSWORD '${SPRYKER_DB_PASSWORD}';"
        psql -lqt -h ${SPRYKER_DB_HOST} -U ${SPRYKER_DB_USERNAME} | grep ${SPRYKER_DB_DATABASE} > /dev/null 2>&1 \
            || psql -h ${SPRYKER_DB_HOST} -U ${SPRYKER_DB_ROOT_USERNAME} -tc "CREATE DATABASE \"${SPRYKER_DB_DATABASE}\" OWNER = ${SPRYKER_DB_USERNAME} ENCODING = 'UTF-8';"

EOF
}


export -f doesDatabaseHaveTables
export -f initDatabase
