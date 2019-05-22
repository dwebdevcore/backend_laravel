#!/usr/bin/env bash

dnf install php php-common php-mysqlnd php-gd php-gmp php-fpm php-soap php-opcache php-pecl-redis composer -y

sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g' /etc/php.ini

sed -i 's/;listen.owner = nobody/listen.owner = nginx/g' /etc/php-fpm.d/www.conf
sed -i 's/;listen.group = nobody/listen.group = nginx/g' /etc/php-fpm.d/www.conf
sed -i 's/;listen.mode = 0660/listen.mode = 0777/g' /etc/php-fpm.d/www.conf
sed -i 's/user = apache/user = nginx/g' /etc/php-fpm.d/www.conf
sed -i 's/group = apache/group = nginx/g' /etc/php-fpm.d/www.conf
sed -i 's/;clear_env = no/clear_env = no/g' /etc/php-fpm.d/www.conf

chown root:nginx /var/lib/php/session /var/lib/php/opcache /var/lib/php/wsdlcache

mkdir -p /var/run/php-fpm/

composer config -g repo.packagist composer https://packagist.org
