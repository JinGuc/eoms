#!/bin/bash
cur_dir=$(pwd)
${cur_dir}/lamp.sh --apache_option 1 --db_option 1 --php_option 1 --supervisord 1 --www 1 --snmp 1 --py-eoms 1 --db_manage_modules phpmyadmin