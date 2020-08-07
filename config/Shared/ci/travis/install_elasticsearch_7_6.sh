#!/bin/bash

mkdir /home/travis/elasticsearch
wget --no-verbose -O - https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-7.6.2-linux-x86_64.tar.gz | tar xz --directory=/home/travis/elasticsearch --strip-components=1 > /dev/null
/home/travis/elasticsearch/bin/elasticsearch --daemonize
