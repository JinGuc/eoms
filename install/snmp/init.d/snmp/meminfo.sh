#!/bin/bash
phymem=`free | grep "Mem:" |awk '{print $2}'`
phymemused=`free | grep 'Mem:' | awk '{print $3}'`
 
#echo $phymem
#echo $phymemused
 
#memUsedPercent=`awk 'BEGIN{printf"%.2f\n",('$phymemused'/'$phymem')*100}'`
#memstr=`free | grep "Mem:" |awk '{print $2,$3}'`
#memstr=${memstr/ /,};
#echo $memstr
#IFS="," read -a mem <<< $memstr
#phymemused=${mem[1]}
#phymem=${mem[0]}
memUsedPercent=`awk 'BEGIN{printf"%.2f\n",('$phymemused'/'$phymem')*100}'`
osname=`cat /etc/redhat-release`
year=`date -d '' +%Y`
month=`date -d '' +%b`
day=`date -d '' +%d`
############获取当天登陆成功次数#############
a=0
IFS_old=$IFS
IFS=$'\n'
for line in `last -F|grep "${year}"|grep "${month} ${day}"`
do
#echo $line
a=$[$a+1]
IFS=$IFS_old
done
############获取当天登陆成功次数#############
############获取当天登陆错误次数#############
b=0
IFS_old=$IFS
IFS=$'\n'
for line in `lastb -F|grep "${year}"|grep "${month} ${day}"`
do
#echo $line
b=$[$b+1]
IFS=$IFS_old
done
############获取当天登陆错误次数#############

echo "{'memToal':'$phymem','memUsed':'$phymemused','memUsedPercent':'$memUsedPercent','osname':'$osname','today_login_success_totalCount':'$a','today_login_error_totalCount':'$b'}"