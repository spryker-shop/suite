#!/usr/bin/env bash
sudo service mysql stop || echo "mysql not stopped"
sudo apt-get remove --purge mysql*
sudo apt-get purge mysql*
sudo apt-get autoremove
sudo apt-get install libaio1 libmecab2
sudo wget https://downloads.mysql.com/archives/get/file/mysql-common_5.7.19-1ubuntu14.04_amd64.deb
sudo dpkg -i mysql-common_5.7.19-1ubuntu14.04_amd64.deb
sudo wget https://downloads.mysql.com/archives/get/file/mysql-community-client_5.7.19-1ubuntu14.04_amd64.deb
sudo dpkg -i mysql-community-client_5.7.19-1ubuntu14.04_amd64.deb
sudo wget https://downloads.mysql.com/archives/get/file/mysql-client_5.7.19-1ubuntu14.04_amd64.deb
sudo dpkg -i mysql-client_5.7.19-1ubuntu14.04_amd64.deb
sudo wget https://downloads.mysql.com/archives/get/file/mysql-community-server_5.7.19-1ubuntu14.04_amd64.deb
sudo dpkg -i mysql-community-server_5.7.19-1ubuntu14.04_amd64.deb
