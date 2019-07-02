#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ./constants.sh
popd > /dev/null

# ------------------
function verbose()
{
    VERBOSE=${VERBOSE:-0}
    [ "${VERBOSE}" == "1" ] && echo -e ${@}
    return ${__TRUE}
}
export -f verbose

# ------------------
function error()
{
    echo -e ${@} >&2
    return ${__TRUE}
}
export -f error

# ------------------
if [ $(tput colors) -gt 0 ]; then
  RED="\033[1;31m"
  BLUE="\033[1;34m"
  CYAN="\033[1;36m"
  GREEN="\033[1;32m"
  YELLOW="\033[1;33m"
  BLUE="\033[1;34m"
  MAGENTA="\033[1;35m"
  LGRAY="\033[1;37m"
  DGRAY="\033[1;90m"
  BLACK="\033[1;30m"
  WHITE="\033[1;97m"

  BACKYELLOW="\033[43m"
  BACKLGRAY="\033[47m"
  BACKDGRAY="\033[100m"

  BOLD="\033[1m"
  DIM="\033[2m"
  ITALIC="\033[3m"
  UNDERLINE="\033[4m"

  NC="\033[0m"    # No Color
  CLEAR="\033[1K" # Clear everything from cursor to beginning of the line

  INFO=${YELLOW}
  WARN=${RED}
  OK=${GREEN}

  BLOCKLIGHT="${BACKLGRAY}${WHITE}\n\n    "
  BLOCKDARK="${BACKDGRAY}${BLACK}\n\n    "
  BLOCKYELLOW="${BACKYELLOW}${WHITE}\n\n    "
fi
