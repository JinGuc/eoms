# Copyright (C) 2013 - 2022 Jingu <yw@jinguc.com>
# 
#
# Website:  https://www.jinguc.com
# Github:   

#Pre-installation www
www_preinstall_settings(){
echo ""
}
#Install www
install_www(){
dbhost=localhost
dbname=eoms
password=Jingu_${dbname}
if [ "${only_install_www}" == "no" ]; then   
systemctl start mysqld > /dev/null 2>&1
p=${mysql_root_password_}
${mysql_location}/bin/mysql -uroot -p$p << EOF
create database $dbname character set utf8mb4;
grant all privileges on $dbname.* to $dbname@'${dbhost}' identified by "${password}";
flush privileges;
EOF

if [ $? -eq 0 ];then
echo "数据库创建成功"
else
echo "数据库创建失败,本次安装退出........"
exit 0
fi
else
read -p "请输入MySql的root账号密码：" root_password
mysql -uroot -p$root_password << EOF
create database $dbname character set utf8mb4;
grant all privileges on $dbname.* to $dbname@'${dbhost}' identified by "${password}";
flush privileges;
EOF

if [ $? -eq 0 ];then
echo "数据库创建成功"
else
echo "数据库创建失败,本次安装退出........"
exit 0
fi
fi
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
sed -i 's/DB_PASSWORD="Jg_123456!@#"/DB_PASSWORD=${password}/g' ${web_root_dir}/.env
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer install
sed -i "29s/\/\/ protected/protected/g" ${web_root_dir}/app/Providers/RouteServiceProvider.php
/usr/local/php/bin/php artisan migrate
#导入默认数据
/usr/local/php/bin/php artisan db:seed --class=UserSeeder
/usr/local/php/bin/php artisan db:seed --class=ipListSeeder
/usr/local/php/bin/php artisan db:seed --class=WebSettingSeeder
/usr/local/php/bin/php artisan db:seed --class=SnmpOidSeeder
/usr/local/php/bin/php artisan db:seed --class=SnmpRoleSeeder
mkdir ${web_root_dir}/storage
mkdir ${web_root_dir}/storage/logs
chmod -R 777 ${web_root_dir}/storage/logs
chown -R apache:apache ${web_root_dir}/storage
cp -rp ${cur_dir}/conf/laravel-websock.ini /etc/supervisord.d
systemctl restart supervisord
sleep 1
n=$(iptables -nL | grep 8804 | wc -l)
if [ $n -eq 0 ]; then
iptables -I INPUT -p tcp --dport 8804 -j ACCEPT
fi
#以上文件执行完成后就可以为网站添加计划任务了，
FIND_FILE="/var/spool/cron/apache"
FIND_STR="/usr/local/php/bin/php ${web_root_dir}/artisan schedule:run"
# 判断匹配函数，匹配函数不为0，则包含给定字符
f=`grep -c "$FIND_STR" $FIND_FILE`
if [ -z $f ] || [ $f -eq 0 ] || [ ! -f "$FIND_FILE" ];then
echo "
#------------------eoms crontab start------------------
* * * * * /usr/local/php/bin/php ${web_root_dir}/artisan schedule:run > /dev/null
#------------------eoms crontab end------------------
"  >> ${FIND_FILE}
fi
}