#!/bin/bash

set -e

pushd ${BASH_SOURCE%/*} > /dev/null
. ./constants.sh

VERBOSE=0 ./require.sh uname grep sed wc
popd > /dev/null

# ------------------
function getPlatform()
{
  if [ "$(uname)" == "Linux" -a "$(uname -a | grep -v Microsoft | wc -l |sed 's/^ *//')" -eq 1 ] ; then
    echo "linux"
    return 0
  fi

  if [ "$(uname)" == "Darwin" ]; then
    echo "macos"
    return 0
  fi

  echo "windows"
}

export -f getPlatform
