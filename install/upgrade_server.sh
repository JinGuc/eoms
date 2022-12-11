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

upgrade(){
    echo
    _info "开始升级${www_app_name}服务端"
    upgrade_www 2>&1 | tee ${cur_dir}/upgrade_www.log
    upgrade_snmp 2>&1 | tee ${cur_dir}/upgrade_snmp.log
    upgrade_py-eoms 2>&1 | tee ${cur_dir}/upgrade_py-eoms.log
    echo
    _info "${www_app_name}服务端升级成功！"
}

include config
include public
include upgrade_www
include upgrade_snmp
include upgrade_py-eoms
load_config
rootness

while true; do
    read -p "Are you sure upgrade JgOms server?(Default: n) (y/n)" upgrade
    [ -z ${upgrade} ] && upgrade="n"
    upgrade=$(upcase_to_lowcase ${upgrade})
    case ${upgrade} in
        y) upgrade ; break;;
        n) _info "Upgrade cancelled, nothing to do" ; break;;
        *) _warn "Input error, Please only input y or n";;
    esac
done
