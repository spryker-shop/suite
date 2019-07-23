#!/usr/bin/env bash
sudo service mysql stop || echo "mysql not stopped"
sudo apt-get remove --purge mysql*
sudo apt-get purge mysql*
sudo apt-get autoremove
#sudo stop mysql-5.6 || echo "mysql-5.6 not stopped"
#echo mysql-apt-config mysql-apt-config/select-server select mysql-5.7 | sudo debconf-set-selections
#wget http://dev.mysql.com/get/mysql-apt-config_0.7.3-1_all.deb
#sudo dpkg --install mysql-apt-config_0.7.3-1_all.deb
#sudo apt-get update -q
#sudo apt-get install -q -y --allow-unauthenticated -o Dpkg::Options::=--force-confnew mysql-server
#sudo dpkg -l | grep mysql 
#sudo apt-get install mysql-server mysql-client -y -q
sudo apt-get install libaio1 libmecab2
sudo wget https://downloads.mysql.com/archives/get/file/mysql-common_5.7.19-1ubuntu14.04_amd64.deb
sudo dpkg -i mysql-common_5.7.19-1ubuntu14.04_amd64.deb
sudo wget https://downloads.mysql.com/archives/get/file/mysql-community-client_5.7.19-1ubuntu14.04_amd64.deb
sudo dpkg -i mysql-community-client_5.7.19-1ubuntu14.04_amd64.deb
sudo wget https://downloads.mysql.com/archives/get/file/mysql-client_5.7.19-1ubuntu14.04_amd64.deb
sudo dpkg -i mysql-client_5.7.19-1ubuntu14.04_amd64.deb
sudo wget https://downloads.mysql.com/archives/get/file/mysql-community-server_5.7.19-1ubuntu14.04_amd64.deb
sudo dpkg -i mysql-community-server_5.7.19-1ubuntu14.04_amd64.deb
sudo service mysql status
mysql -e "show databases"
#sudo mysql_upgrade --force
