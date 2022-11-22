#!/bin/bash
 phymem=`free | grep "Mem:" |awk '{print $2}'`
 phymemused=`free | grep 'Mem:' | awk '{print $6}'`
 
#echo $phymem
#echo $phymemused
 
memUsedPercent=`awk 'BEGIN{printf"%.2f\n",('$phymemused'/'$phymem')*100}'`
osname=`cat /etc/redhat-release`
############获取当天登陆错误次数#############
year=`date -d '' +%Y`
month=`date -d '' +%b`
day=`date -d '' +%d`
a=0
for line in `lastb -F|grep "${year}"|grep "${moth} ${day}"`
do
#echo $line
a=$[$a+1]
done
############获取当天登陆错误次数#############
############获取硬盘数量#####################
i=0
Disk=""
fdisk -l |grep '/dev' |grep 'GB' |awk -F , '{print $1}' | sed 's/mapper.*//g' | sed '/^$/d' > /root/disk.log
for line in `cat /root/disk.log`
do
line=${line/磁盘/Disk};
line=${line/: /:};
line=${line/Disk/};
line=${line/GB/};
line=${line/：/:};
Disk="$Disk$line@"
done
#echo $Disk;
Disk=${Disk/:@/:};
Disk=${Disk/:@/:};
Disk=${Disk/:@/:};
Disk=${Disk/:@/:};
############获取硬盘数量#####################


Disks=""
OLD_IFS="$IFS"
IFS="@@@"
arr=($Disk)
IFS="$OLD_IFS"
for s in ${arr[@]}
do
IFS=':'
disk_name_arr=($s)
IFS=$OLD_IFS
disk_name=${disk_name_arr[0]}
#echo $disk_name
################获取磁盘总容量######################
totalsize=`df |grep ${disk_name}| awk '{print $2}' |sed '/^\s*$/d' | awk '{sum+=$1} END {print sum}'`
################获取磁盘USED总容量######################
usedsize=`df |grep ${disk_name}| awk '{print $3}' |sed '/^\s*$/d' | awk '{sum+=$1} END {print sum}'`
disk_info_name_used_percent=`echo "scale=2; $usedsize*100/$totalsize" | bc`
#echo $disk_info_name_used_percent
################获取磁盘总indoes######################
totalindoessize=`df -i |grep ${disk_name}| awk '{print $2}' |sed '/^\s*$/d' | awk '{sum+=$1} END {print sum}'`
################获取磁盘USED-indoes######################
usedindoessize=`df -i |grep ${disk_name}| awk '{print $3}' |sed '/^\s*$/d' | awk '{sum+=$1} END {print sum}'`
disk_info_name_indoes_used_percent=`echo "scale=2; $usedindoessize*100/$totalindoessize" | bc`
IFS_old=$IFS
IFS=$'\n'
################获取磁盘读取速度(MB/sec)######################
for rw in `hdparm -t ${disk_name} |grep -v '/dev/*' |awk -F '=' '{print $2}'`
################获取磁盘读取速度(MB/sec)######################
do
#echo $rw
rw=${rw/ /};
rw=${rw/ /};
rw=${rw/MB/};
rw=${rw/sec/};
rw=${rw///};
Disks="$Disks$s:$disk_info_name_used_percent:$disk_info_name_indoes_used_percent:$rw@"
done
IFS=$IFS_old
done
Disks=${Disks::-1}

############获取开启的TCP端口#####################
tcp_port=""
IFS_old=$IFS
IFS=$'\n'
for line in `netstat -ntlp |grep 'tcp*'|grep '0.0.0.0*'|awk -F ' ' '{print $1,$4,$7,$8}'`
do
#echo $line
line=${line/tcp 0.0.0.0:/};
line=${line/tcp6 0.0.0.0:/};
line=${line/tcp6 /};
line=${line/tcp /};
line=${line/ /|};
line=${line/ /};
tcp_port="$tcp_port$line,"
done
IFS=$IFS_old
tcp_port=${tcp_port::-1}
#echo $tcp_port
OLD_IFS=$IFS
IFS=$OLD_IFS
############获取开启的TCP端口#####################
############获取开启的UDP端口#####################
udp_port=""
IFS_old=$IFS
IFS=$'\n'
for line in `netstat -nulp |grep 'udp*'|awk -F ' ' '{print $1,$4,$6}'`
do
#echo $line
line=${line/udp 0.0.0.0:/};
line=${line/udp6 0.0.0.0:/};
line=${line/udp6 /};
line=${line/udp /};
#line=${line/:::/};
line=${line/ /|};
line=${line/ /};
udp_port="$udp_port$line,"
done
IFS=$IFS_old
udp_port=${udp_port::-1}
#echo $tcp_port
OLD_IFS=$IFS
IFS=$OLD_IFS
############获取开启的UDP端口#####################
echo "{'memToal':'$phymem','memUsed':'$phymemused','memUsedPercent':'$memUsedPercent','osname':'$osname','disks':'$Disks','tcp_port':'$tcp_port','udp_port':'$udp_port','login_error_totalCount':'$a'}"