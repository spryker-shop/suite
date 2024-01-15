#!/usr/bin/env bash

set -e

gem update --system
gem --version
gem install mailcatcher --no-document > /dev/null
mailcatcher > /dev/null
