#!/bin/sh
set -e

# If first arg is `-f` or `--some-option`, we assume that command is "php-fpm"
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

# Replace this entrypoint process with container COMMAND
exec "$@"
