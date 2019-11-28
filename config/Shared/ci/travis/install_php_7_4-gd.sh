#!/bin/bash

mkdir /home/travis/php74
wget -O - https://www.php.net/distributions/php-7.4.0.tar.gz | tar xz --directory=/home/travis/php74-gd --strip-components=1 > /dev/null
cd /home/travis/php74/php-7.4.0/ext/gd
phpize
./configure --enable-gd
make
sudo make install
