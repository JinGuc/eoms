#!/bin/bash
cur_dir=$(pwd)
${cur_dir}/lamp.sh --apache_option 1 --db_option 1 --php_option 1 --supervisord_option 1 --www_option 1 --snmp_option 1 --py-eoms_option 1 --db_manage_modules phpmyadmin