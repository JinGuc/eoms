#!/bin/bash
cur_dir=$(pwd)
if [ "$1" = "www" ]; then
    ${cur_dir}/lamp.sh --apache_option 2 --db_option 3 --php_option 2 --supervisord_option 1 --www_option 1 --only_install_www_option 1
else
    ${cur_dir}/lamp.sh --apache_option 1 --db_option 1 --php_option 1 --supervisord_option 1 --www_option 1 --db_manage_modules phpmyadmin
fi