#!/usr/bin/env bash
# DEBIAN_FRONTEND=noninteractive

apt-get update
timedatectl set-timezone Europe/Moscow
apt-get install -yqq mc;

##
# MySql
##
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

apt-get install -yqq mysql-server

echo 'create database silex' | mysql -uroot -proot
echo 'create database silex_tests' | mysql -uroot -proot
echo "use mysql; update user set host='%' where user='root' and host='127.0.0.1';flush privileges;" | mysql -uroot -proot

cp /vagrant/vagrant/config/mysql.cnf /etc/mysql/conf.d/


echo Installing PHP...

apt-get install -yqq php5-cli php5-fpm php5-curl php5-pgsql php5-mcrypt php5-intl libxrender1 libfontconfig1 php5-mysql
cp /vagrant/vagrant/config/php.ini /etc/php5/fpm/conf.d/
cp /vagrant/vagrant/config/php.ini /etc/php5/cli/conf.d/



echo Installing Nginx...

apt-get install -yqq nginx
cp /vagrant/vagrant/config/nginx.conf /etc/nginx/sites-enabled/
service nginx restart


echo Installing Composer...

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer


#su vagrant -l /vagrant/vagrant/project.sh
