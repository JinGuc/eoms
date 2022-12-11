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
# Website:  https://www.jinguc.com
# Github:   

#upgrade www
upgrade_www(){
dbhost=localhost
dbname=eoms
p=${mysql_root_password_}
password=Jingu_${dbname}
if [ ! -d "${mysql_data_back_dir}" ]; then
    mkdir ${mysql_data_back_dir}
fi
time=$(date "+%Y%m%d%H%M%S")
${mysql_location}/bin/mysqldump -uroot -p${p} ${dbname} > ${mysql_data_back_dir}/${dbname}_${time}.sql

cd ${cur_dir}
cd ../
cur_dir_=$(pwd)
cp -rp ${cur_dir_}/${web_dir} ${www_home_dir}
#导入数据库文件
cd ${web_root_dir}
chmod -R 777 ${web_root_dir}/storage/
chmod -R 777 ${web_root_dir}/bootstrap/cache/

sed -i "s|APP_URL=http://47.104.96.84|APP_URL=http://127.0.0.1|g" ${web_root_dir}/.env
sed -i "s/DB_HOST=127.0.0.1/DB_HOST=${dbhost}/g" ${web_root_dir}/.env
sed -i "s/DB_USERNAME=root/DB_USERNAME=${dbname}/g" ${web_root_dir}/.env
sed -i "s/Jg_123456!@#/DB_PASSWORD=${password}/g" ${web_root_dir}/.env
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer install
sed -i "29s/\/\/ protected/protected/g" ${web_root_dir}/app/Providers/RouteServiceProvider.php

migrate_command=$(/usr/local/php/bin/php artisan migrate)
FINDSTR="SQL"
if [[ $migrate_command =~ $FINDSTR ]];then
    echo "${www_app_name}数据表导入失败,本次安装退出........"
    exit 0
else
    #导入默认数据
    /usr/local/php/bin/php artisan db:seed --class=UserSeeder
    /usr/local/php/bin/php artisan db:seed --class=ipListSeeder
    /usr/local/php/bin/php artisan db:seed --class=WebSettingSeeder
    /usr/local/php/bin/php artisan db:seed --class=SnmpOidSeeder
    /usr/local/php/bin/php artisan db:seed --class=SnmpRoleSeeder
fi
if [ ! -d "${web_root_dir}/storage" ]; then
    mkdir ${web_root_dir}/storage
fi
if [ ! -d "${web_root_dir}/storage/logs" ]; then
    mkdir ${web_root_dir}/storage/logs
fi
chmod -R 777 ${web_root_dir}/storage/logs
chown -R apache:apache ${web_root_dir}/storage
#cp -rp ${cur_dir}/conf/laravel-websock.ini /etc/supervisord.d
systemctl restart supervisord
}