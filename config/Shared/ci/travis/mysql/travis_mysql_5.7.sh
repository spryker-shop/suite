#!/usr/bin/env bash
sudo service mysql stop || echo "mysql not stopped"
sudo apt-get remove --purge mysql* > /dev/null
sudo apt-get purge mysql* > /dev/null
sudo apt-get autoremove > /dev/null
sudo apt-get install libaio1 libmecab2 > /dev/null
sudo wget https://downloads.mysql.com/archives/get/p/23/file/mysql-common_5.7.28-1debian9_amd64.deb > /dev/null
sudo dpkg -i mysql-common_5.7.28-1debian9_amd64.deb > /dev/null
sudo wget https://downloads.mysql.com/archives/get/p/23/file/mysql-community-client_5.7.28-1debian9_amd64.deb > /dev/null
sudo dpkg -i mysql-community-client_5.7.28-1debian9_amd64.deb > /dev/null
sudo wget https://downloads.mysql.com/archives/get/p/23/file/mysql-client_5.7.28-1debian9_amd64.deb > /dev/null
sudo dpkg -i mysql-client_5.7.28-1debian9_amd64.deb > /dev/null
sudo wget https://downloads.mysql.com/archives/get/p/23/file/mysql-community-server_5.7.28-1debian9_amd64.deb > /dev/null
sudo dpkg -i mysql-community-server_5.7.28-1debian9_amd64.deb > /dev/null
