#!/usr/bin/env bash
# Copyright (C) 2013 - 2022 Jingu <yw@jinguc.com>
# 
# This file is part of the LAMP script.
#
# LAMP is a powerful bash script for the installation of 
# Apache + PHP + MySQL/MariaDB and so on.
# You can install Apache + PHP + MySQL/MariaDB in an very easy way.
# Just need to input numbers to choose what you want to install before installation.
# And all things will be done in a few minutes.
#
# System Required:  CentOS 7+ / Debian 9+ / Ubuntu 18+
# Description:  Uninstall LAMP(Linux + Apache + MySQL/MariaDB + PHP )
# Website:  https://www.jinguc.com
# Github:   

PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH

cur_dir=$(cd -P -- "$(dirname -- "$0")" && pwd -P)

include(){
    local include=$1
    if [[ -s ${cur_dir}/include/${include}.sh ]]; then
        . ${cur_dir}/include/${include}.sh
    else
        echo "Error:${cur_dir}/include/${include}.sh not found, shell can not be executed."
        exit 1
    fi
}
uninstall_jgoms(){
    echo
    _info "卸载金鼓数据库"
    read -p "请输入MySql的root账号密码：" root_password
mysql -uroot -p$root_password << EOF
DROP DATABASE IF EXISTS ${dbname};
drop user ${dbuser};
flush privileges;
EOF
    _info "Success"
    echo 
    _info "卸载SNMP"
    systemctl stop snmpd
    yum -y remove perl-ExtUtils-MakeMaker package libperl-dev bc sysstat net-snmp net-snmp-utils
    rm -rf /opt/snmp
    rm -rf /etc/snmp
    _info "Success"
    echo
    _info "卸载Supervisord"
    systemctl stop supervisord
    yum -y remove supervisor
    rm -rf /etc/supervisord.d
    _info "Success"
    echo
    _info "卸载JgOmsWeb"
    rm -rf ${web_root_dir}
    _info "Success"
    echo
    _info "Successfully uninstall JgOms"
}
uninstall_lamp(){
    _info "uninstalling Apache"
    if [ -f /etc/init.d/httpd ] && [ $(ps -ef | grep -v grep | grep -c "httpd") -gt 0 ]; then
        systemctl stop httpd > /dev/null 2>&1
    fi
    rm -f /etc/init.d/httpd
    rm -rf ${apache_location} ${apache_location}.bak /usr/sbin/httpd /var/log/httpd /etc/logrotate.d/httpd /var/spool/mail/apache /home/apache
    crontab -u apache -r
    _info "Success"
    echo
    _info "uninstalling MySQL"
    echo
    _info "卸载${www_app_name}数据库"
    read -p "请输入MySql的root账号密码：" root_password
    mysql -uroot -p$root_password << EOF
    DROP DATABASE IF EXISTS ${dbname};
    drop user ${dbuser};
    flush privileges;
EOF
    if [ -f /etc/init.d/mysqld ] && [ $(ps -ef | grep -v grep | grep -c "mysqld") -gt 0 ]; then
        systemctl stop mysqld > /dev/null 2>&1
    fid/
    rm -f /etc/init.mysqld
    rm -rf ${mysql_location} ${mariadb_location} ${mysql_location}.bak ${mariadb_location}.bak /usr/bin/mysqldump /usr/bin/mysql /etc/my.cnf /etc/ld.so.conf.d/mysql.conf
    _info "Success"
    echo
    _info "uninstalling PHP"
    rm -rf ${php_location} ${php_location}.bak /usr/bin/php /usr/bin/php-config /usr/bin/phpize /usr/bin/php-cgi /etc/php.ini
    _info "Success"
    echo 
    _info "uninstalling SNMP"
    systemctl stop snmpd
    yum -y remove perl-ExtUtils-MakeMaker package libperl-dev bc sysstat net-snmp net-snmp-utils
    rm -rf /opt/snmp
    rm -rf /etc/snmp
    _info "Success"
    echo
    _info "uninstalling Supervisord"
    systemctl stop supervisord
    yum -y remove supervisor
    rm -rf /etc/supervisord.d
    _info "Success"
    echo
    _info "uninstalling JgOmsWeb"
    rm -rf ${web_root_dir}
    _info "Success"
    echo
    _info "uninstalling others software"
    if [ -f /etc/init.d/memcached ] && [ $(ps -ef | grep -v grep | grep -c "memcached") -gt 0 ]; then
        /etc/init.d/memcached stop > /dev/null 2>&1
    fi
    rm -f /etc/init.d/memcached
    rm -fr ${depends_prefix}/memcached /usr/bin/memcached
    if [ -f /etc/init.d/redis-server ] && [ $(ps -ef | grep -v grep | grep -c "redis-server") -gt 0 ]; then
        /etc/init.d/redis-server stop > /dev/null 2>&1
    fi
    rm -f /etc/init.d/redis-server
    rm -rf ${depends_prefix}/redis
    rm -rf /usr/local/lib/libcharset* /usr/local/lib/libiconv* /usr/local/lib/charset.alias /usr/local/lib/preloadable_libiconv.so
    rm -rf ${depends_prefix}/libiconv
    rm -rf ${depends_prefix}/pcre
    rm -rf ${depends_prefix}/cmake
    rm -rf ${openssl_location} /etc/ld.so.conf.d/openssl.conf
    rm -rf /usr/lib/libnghttp2.*
    rm -rf /usr/lib/libargon2.*
    rm -rf /usr/local/lib/libmcrypt.*
    rm -rf /usr/local/lib/libmhash.*
    rm -rf /usr/local/bin/iconv
    rm -rf /usr/local/bin/re2c
    rm -rf /usr/local/bin/re2go
    rm -rf /usr/local/bin/re2rust
    rm -rf /usr/local/bin/mcrypt
    rm -rf /usr/local/bin/mdecrypt
    rm -rf /etc/ld.so.conf.d/locallib.conf
    rm -rf /etc/ld.so.conf.d/locallib64.conf
    rm -rf ${web_root_dir}/phpmyadmin
    rm -rf ${web_root_dir}/kod
    rm -rf ${web_root_dir}/xcache /tmp/{pcov,phpcore}
    _info "Success"
    echo
    _info "Successfully uninstall JgOms"
}

include config
include public
load_config
rootness

while true; do
    echo "确定要卸载${www_app_name}?"
    echo "1.只卸载${www_app_name}及${www_app_name}所使用的数据库"
    echo "2.运行环境（Apache、Mysql、PHP）及${www_app_name}全部卸载"
    read -p "请选择 (默认: 1) " uninstall
    [ -z ${uninstall} ] && uninstall="1"
    case ${uninstall} in
        1) uninstall_jgoms ; break;;
        2) uninstall_lamp ; break;;
        *) _warn "Input error, Please only input 1 or 2";;
    esac
done
