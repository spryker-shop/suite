#!/usr/bin/env bash

set -e

gem install sqlite3 -v 1.7.0
gem install mailcatcher --no-document > /dev/null
mailcatcher > /dev/null
