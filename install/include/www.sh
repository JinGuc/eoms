# Copyright (C) 2013 - 2022 Jingu <yw@jinguc.com>
# 
#
# Website:  https://www.jinguc.com
# Github:   

#Pre-installation www
name=eoms
p=Jingu.com
mysql -uroot -p$p << EOF
create database $name character set utf8;
grant all privileges on $name.* to $name@'localhost' identified by "Jingu_${name}";
flush privileges;
EOF
 
if [ $? -eq 0 ];then
echo "数据库创建成功"
else
echo "数据库创建失败"
fi
cd ../../
cur_dir=$(pwd)
cp -rp ${cur_dir}/www ${web_root_dir}
#导入数据库文件
DB_USERNAME=root
DB_PASSWORD="Jg_123456!@#"
cd ${web_root_dir}
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
sed -i 's/^DB_USERNAME=root/DB_USERNAME=${name}/' ${web_root_dir}/.env
/usr/local/php/bin/php artisan migrate
#导入默认数据
/usr/local/php/bin/php artisan db:seed --class=UserSeeder
/usr/local/php/bin/php artisan db:seed --class=ipListSeeder
/usr/local/php/bin/php artisan db:seed --class=WebSettingSeeder
/usr/local/php/bin/php artisan db:seed --class=SnmpOidSeeder
/usr/local/php/bin/php artisan db:seed --class=SnmpRoleSeeder
#以上文件执行完成后就可以为网站添加计划任务了，
echo "
#------------------eoms crontab start------------------
* * * * *   cd /home/data/www && /usr/local/php/bin/php artisan schedule:run > /dev/null
#------------------eoms crontab end------------------
"  >> /etc/crontab

