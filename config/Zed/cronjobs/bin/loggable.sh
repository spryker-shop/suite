#!/usr/bin/env bash

# Usage: ./loggable.sh <command>
# Example: ./loggable.sh vendor/bin/console transfer:generate
#
# This bash script allows you to run any Spryker console command and have the output and log data from that command be
# forwarded to the container's stdout/stderr files (/proc/1/fd/{1,2}), which results in the content being visible in CloudWatch.
#
# This file exists as a workaround, since we cannot write directly to the container's stdout/stderr files using
# php's fopen function (the cause is unknown; bash and python works just fine, error_log() also works).
#
# This script opens a set of temporary named pipes and continuously moves content from the pipes to the container's
# stdout/stderr files to get around the previously mentioned limitation.
#
# Because Linux child processes inherit the parent process' environment variables, they will also inherit the log
# destination set in this script. This is useful when using `console queue:worker:start`, which starts multiple child
# processes which all would otherwise write log messages to php://stderr, which is not propagated anywhere.

set -euo pipefail

if [ $# -eq 0 ]; then
    echo "No arguments provided" >&2
    echo "Usage: ./loggable.sh <command>" >&2
    exit 1
fi

TMPDIR=$(mktemp --directory)
cleanup() {
    kill $(jobs -p)
    rm -rf "$TMPDIR"
}
trap cleanup ERR EXIT

PIPE_STDOUT=$TMPDIR/stdout
PIPE_STDERR=$TMPDIR/stderr
mkfifo "$PIPE_STDOUT" "$PIPE_STDERR"

# these point to stdout/stderr for the container's entrypoint by default.
CONTAINER_STDOUT="${CONTAINER_STDOUT:-/proc/1/fd/1}"
CONTAINER_STDERR="${CONTAINER_STDERR:-/proc/1/fd/2}"

# <> keeps the pipe open for continuous reading
cat <> "$PIPE_STDOUT" | tee "$CONTAINER_STDOUT" &
cat <> "$PIPE_STDERR" | tee "$CONTAINER_STDERR" &

export SPRYKER_LOG_STDOUT=$PIPE_STDOUT
export SPRYKER_LOG_STDERR=$PIPE_STDERR

"$@" >> "$PIPE_STDOUT" 2>> "$PIPE_STDERR"

exit $?
