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
        FINDSTR=2.4
        httpdV=$(httpd -v | grep version)
        echo
        echo $httpdV
        if [[ $httpdV =~ $FINDSTR ]];then
            _info "Apache版本一致,安装继续........"
            check_port
        else
             _info "${www_app_name}运行环境需要Apache版本为2.4,本次安装退出........"
            echo
            exit 0
        fi 
        FINDSTR=5.7
        mysqlV=$(mysql -V)
        echo
        echo $mysqlV
        if [[ $mysqlV =~ $FINDSTR ]];then
            _info "MySql版本一致,安装继续........"
        else
            _info "${www_app_name}运行环境需要MySql版本为5.7,本次安装退出........"
            exit 0
        fi
        FINDSTR=7.4
        phpV=$(php -v | grep PHP |grep -v 'Copyright')
        echo
        echo $phpV
        if [[ $phpV =~ $FINDSTR ]];then
            _info "PHP版本一致,安装继续........"
            findsnmp=$(php -m | grep snmp)
            if [ -n "$findsnmp" ]; then
            _info "PHP已开启snmp扩展,安装继续........"
            else
            _info "PHP未开启snmp扩展,请先安装snmp扩展,本次安装退出........"
            exit 0
            fi
        else
            echo "${www_app_name}运行环境需要PHP版本为7.4,本次安装退出........"
            exit 0
        fi 
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
if [[ -d "${www_home_dir}" ]]; then
    #导入数据库文件
    cd ${web_root_dir}
    else
        echo
        _info "复制${www_app_name}文件失败,本次安装退出........"
        exit 0
fi
chmod -R 777 ${web_root_dir}/storage/
chmod -R 777 ${web_root_dir}/bootstrap/cache/

sed -i "s|APP_URL=http://47.104.96.84|APP_URL=http://127.0.0.1|g" ${web_root_dir}/.env
sed -i "s/DB_HOST=127.0.0.1/DB_HOST=${dbhost}/g" ${web_root_dir}/.env
sed -i "s/DB_USERNAME=root/DB_USERNAME=${dbname}/g" ${web_root_dir}/.env
sed -i "s/Jg_123456!@#/${password}/g" ${web_root_dir}/.env
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer install
sed -i "29s/\/\/ protected/protected/g" ${web_root_dir}/app/Providers/RouteServiceProvider.php

/usr/local/php/bin/php artisan migrate:reset
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
check_port(){
if [ "${only_install_www}" == "yes" ]; then 
read -p "请输入Apache站点配置文件绝对路径：" virtual_site_conf_file
if [ -f "${virtual_site_conf_file}" ]; then
FIND_FILE=${virtual_site_conf_file}
FIND_STR="localhost:8013"
# 判断匹配函数，匹配函数不为0，则包含给定字符
f=`grep -c "$FIND_STR" $FIND_FILE`
$findport=$(netstat -ntlp | grep 8013)
if [ -z $f ] || [ $f -eq 0 ] || [ ! -f "$FIND_FILE" ] || [ -n "$findport" ] ;then

cat > ${virtual_site_conf_file} <<EOF
Listen 8013
<VirtualHost _default_:8013>
ServerName localhost:8013
DocumentRoot ${web_root_dir}/public
<Directory ${web_root_dir}/public>
    SetOutputFilter DEFLATE
    Options FollowSymLinks
    AllowOverride All
    Order Deny,Allow
    Allow from All
    DirectoryIndex index.php index.html index.htm
</Directory>
</VirtualHost>
EOF
else
echo "Apache端口8013已被占用,请先关闭8013端口,本次安装退出........"
exit 0
fi
else
    echo "Apache站点配置文件不存在,本次安装退出........"
    exit 0
fi
fi
}