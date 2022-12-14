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
    uninstall_eoms_mysql
    echo 
    _info "开始卸载SNMP"
    systemctl stop snmpd
    yum -y remove perl-ExtUtils-MakeMaker package libperl-dev bc sysstat net-snmp net-snmp-utils
    rm -rf /opt/snmp
    rm -rf /etc/snmp
    _info "成功"
    echo
    _info "开始卸载Supervisord"
    supervisorctl stop py-eoms
    systemctl stop supervisord
    yum -y remove supervisor
    rm -rf /etc/supervisord.d
    _info "成功"
    echo
    _info "开始卸载${www_app_name}"
    rm -rf ${web_root_dir}
    rm ${apache_location}/conf/vhost/jgoms.conf 
    FIND_FILE="/var/spool/cron/apache"
    if [ -f "$FIND_FILE" ];then
        sed -i '/jgoms/d' ${FIND_FILE}
        sed -i '/schedule:run/d' ${FIND_FILE} 
    fi
    _info "成功"
    echo
    _info "成功卸载${www_app_name}"
}
uninstall_lamp(){
    echo
    _info "开始卸载Apache"
    if [ -f /etc/init.d/httpd ] && [ $(ps -ef | grep -v grep | grep -c "httpd") -gt 0 ]; then
        systemctl stop httpd > /dev/null 2>&1
    fi
    rm -f /etc/init.d/httpd
    rm -rf ${apache_location} ${apache_location}.bak /usr/sbin/httpd /var/log/httpd /etc/logrotate.d/httpd /var/spool/mail/apache /home/apache
    crontab -u apache -r
    _info "成功"
    echo
    _info "开始卸载MySQL"
    uninstall_eoms_mysql
    if [ -f /etc/init.d/mysqld ] && [ $(ps -ef | grep -v grep | grep -c "mysqld") -gt 0 ]; then
        systemctl stop mysqld > /dev/null 2>&1
    fi
    rm -f /etc/init.mysqld
    rm -rf ${mysql_location} ${mariadb_location} ${mysql_location}.bak ${mariadb_location}.bak /usr/bin/mysqldump /usr/bin/mysql /etc/my.cnf /etc/ld.so.conf.d/mysql.conf
    _info "成功"
    echo
    _info "开始卸载PHP"
    rm -rf ${php_location} ${php_location}.bak /usr/bin/php /usr/bin/php-config /usr/bin/phpize /usr/bin/php-cgi /etc/php.ini
    _info "成功"
    echo 
    _info "开始卸载SNMP"
    systemctl stop snmpd
    yum -y remove perl-ExtUtils-MakeMaker package libperl-dev bc sysstat net-snmp net-snmp-utils
    rm -rf /opt/snmp
    rm -rf /etc/snmp
    _info "成功"
    echo
    _info "开始卸载Supervisord"
    supervisorctl stop py-eoms
    systemctl stop supervisord
    yum -y remove supervisor
    rm -rf /etc/supervisord.d
    _info "成功"
    echo
    _info "开始卸载${www_app_name}"
    rm -rf ${web_root_dir}
    rm ${apache_location}/conf/vhost/jgoms.conf 
    FIND_FILE="/var/spool/cron/apache"
    if [ -f "$FIND_FILE" ];then
        sed -i '/jgoms/d' ${FIND_FILE}
        sed -i '/schedule:run/d' ${FIND_FILE} 
    fi
    _info "成功"
    echo
    _info "开始卸载其他依赖软件"
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
    _info "成功"
    echo
    _info "成功卸载${www_app_name}"
}
uninstall_eoms_mysql(){
echo
_info "开始卸载${www_app_name}数据库"
read -p "请输入MySQL的root账号密码：" root_password
mysql -uroot -p$root_password << EOF
    DROP DATABASE IF EXISTS ${dbname};
    use mysql;
    delete from user where user='${dbuser}';
    delete from db where user='${dbuser}' and db='${dbname}';
    flush privileges;
EOF
if [ $? -eq 0 ];then
echo "卸载${www_app_name}数据库成功"
else
echo "卸载${www_app_name}数据库失败,请手动操作........"
fi
}
uninstall_jgoms_agent(){
    echo 
    _info "开始卸载SNMP"
    systemctl stop snmpd
    yum -y remove perl-ExtUtils-MakeMaker package libperl-dev bc sysstat net-snmp net-snmp-utils
    rm -rf /opt/snmp
    rm -rf /etc/snmp
    _info "成功"
    echo
    _info "开始卸载Supervisord"
    supervisorctl stop py-eoms
    systemctl stop supervisord
    yum -y remove supervisor
    rm -rf /etc/supervisord.d
    _info "成功"
    _info "成功卸载${www_app_name}客户端"    
}
include config
include public
load_config
rootness
if [ "$1" = "server" ]; then
while true; do
    echo "确定要卸载${www_app_name}吗?"
    echo "1.只卸载${www_app_name}及${www_app_name}所使用的数据库;"
    echo "2.全部卸载(运行环境（Apache、MySQL、PHP）及${www_app_name});"
    read -p "请选择[1-2] (默认: 1) " uninstall
    [ -z ${uninstall} ] && uninstall="1"
    case ${uninstall} in
        1) uninstall_jgoms ; break;;
        2) uninstall_lamp ; break;;
        *) _warn "Input error, Please only input 1 or 2";;
    esac
done
elif [ "$1" = "agent" ]; then
while true; do
    read -p "确定要卸载${www_app_name}客户端吗?请选择[y or n] (默认: n) " uninstall
    [ -z ${uninstall} ] && uninstall="n"
    case ${uninstall} in
        y) uninstall_jgoms_agent ; break;;
        n) _info "退出卸载${www_app_name}客户端"; break;;
        *) _warn "Input error, Please only input 1 or 2";;
    esac
done
else
    echo "Input error!"
    exit 0
fi
