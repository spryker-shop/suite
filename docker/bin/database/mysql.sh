#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ../constants.sh
popd > /dev/null

function doesDatabaseHaveTables()
{
    tableCount=$(execSpryker 'VERBOSE=0 MYSQL_PWD="${SPRYKER_DB_ROOT_PASSWORD}" mysql -h ${SPRYKER_DB_HOST} -u ${SPRYKER_DB_ROOT_USERNAME} -e "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = \"${SPRYKER_DB_DATABASE}\"" | wc -l |  sed "s/^ *//" || echo 0')

    [ "$tableCount" -gt 0 ] && return ${__TRUE} || return ${__FALSE}
}

function initDatabase()
{
    execSpryker 'VERBOSE=0 MYSQL_PWD="${SPRYKER_DB_ROOT_PASSWORD}" mysql -h ${SPRYKER_DB_HOST} -u root -e "CREATE DATABASE IF NOT EXISTS \`${SPRYKER_DB_DATABASE}\` CHARACTER SET \"utf8\"; GRANT ALL PRIVILEGES ON \`${SPRYKER_DB_DATABASE}\`.* TO \"${SPRYKER_DB_USERNAME}\"@\"%\" IDENTIFIED BY \"${SPRYKER_DB_PASSWORD}\" WITH GRANT OPTION;"'
}

export -f doesDatabaseHaveTables
export -f initDatabase
