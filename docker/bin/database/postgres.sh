#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ../constants.sh
popd > /dev/null

function doesDatabaseHaveTables()
{
    tableCount=$(execSprykerMultiString <<'EOF'
        export VERBOSE=0
        export PGPASSWORD="${SPRYKER_DB_ROOT_PASSWORD}"
        psql -lqt -h ${SPRYKER_DB_HOST} -U "${SPRYKER_DB_ROOT_USERNAME}" | grep ${SPRYKER_DB_DATABASE} > /dev/null 2>&1 \
            && psql -h ${SPRYKER_DB_HOST} -U "${SPRYKER_DB_ROOT_USERNAME}" "${SPRYKER_DB_DATABASE}" -c "SELECT count(*) FROM information_schema.tables WHERE table_catalog = '${SPRYKER_DB_DATABASE}';" -t 2>&1 | tr -d ' \n\r' \
            || echo 0
EOF
)

    [ "${tableCount}" -gt 0 ] && return ${__TRUE} || return ${__FALSE}
}

function initDatabase()
{
    execSprykerMultiString <<'EOF'
        export VERBOSE=0
        export PGPASSWORD="${SPRYKER_DB_ROOT_PASSWORD}"
        psql -h ${SPRYKER_DB_HOST} -U "${SPRYKER_DB_ROOT_USERNAME}" -tc "SELECT COUNT(*) FROM pg_catalog.pg_roles WHERE rolname = '${SPRYKER_DB_USERNAME}'" | grep -q 1 \
            || psql -h ${SPRYKER_DB_HOST} -U "${SPRYKER_DB_ROOT_USERNAME}" -c "CREATE ROLE \"${SPRYKER_DB_USERNAME}\" LOGIN PASSWORD '${SPRYKER_DB_PASSWORD}';"
        psql -lqt -h ${SPRYKER_DB_HOST} -U "${SPRYKER_DB_USERNAME}" | grep ${SPRYKER_DB_DATABASE} > /dev/null 2>&1 \
            || psql -h ${SPRYKER_DB_HOST} -U "${SPRYKER_DB_ROOT_USERNAME}" -tc "CREATE DATABASE \"${SPRYKER_DB_DATABASE}\" OWNER = \"${SPRYKER_DB_USERNAME}\" ENCODING = 'UTF-8' LC_COLLATE='en_US.UTF-8' LC_CTYPE='en_US.UTF-8' CONNECTION LIMIT=-1 TEMPLATE=\"template0\";"
        psql -h ${SPRYKER_DB_HOST} -U "${SPRYKER_DB_ROOT_USERNAME}" -tc "GRANT ALL PRIVILEGES ON DATABASE \"${SPRYKER_DB_DATABASE}\" TO \"${SPRYKER_DB_ROOT_USERNAME}\"";
EOF
}

export -f doesDatabaseHaveTables
export -f initDatabase
