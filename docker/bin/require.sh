#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ./constants.sh
. ./console.sh
popd > /dev/null

# ------------------
# Checking that all required software is installed and accessible
function checkRequirements()
{
    verbose -n "${INFO}Checking requirements...${NC}"

    for binary in "$@";
    do
        case ${binary} in
            "-"*)
                # skipping arguments started with '-'
                continue
                ;;
        esac

        local binPath=$(which ${binary} || true)

        if [ -z "${binPath}" ]; then
            verbose ""
            error "${WARN}'${binary}' is not found. Please, make sure this application is installed and added to PATH.${NC}"
            exit 1
        fi
    done

    verbose "[OK]"
}

checkRequirements $@
