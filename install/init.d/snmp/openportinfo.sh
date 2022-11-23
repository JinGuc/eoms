#!/bin/bash
local_ip=`ifconfig -a|grep inet|grep -v 127.0.0.1|grep -v inet6|awk '{print $2}'|tr -d "addr:"​`
#echo "${local_ip}"
############获取开启的TCP端口#####################
tcp_port=""
IFS_old=$IFS
IFS=$'\n'
replace=''
for line in `netstat -ntlp |grep 'tcp*'|awk -F ' ' '{print $1,$2,$3,$4,$7,$8}'`
do
#echo $line
line=${line/tcp6 0.0.0.0:/};
line=${line/tcp 0.0.0.0:/};
line=${line/0.0.0.0:/};
line=${line/127.0.0.1:/};
line=${line/tcp6 /};
line=${line/tcp /};
line=${line// /|};
line=${line// /};
#for var in ${local_ip[@]}
#do
#done
tcp_port="$tcp_port$line,"
done
tcp_port=${tcp_port//|,/,};
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
for line in `netstat -nulp |grep 'udp*'|awk -F ' ' '{print $1,$2,$3,$4,$6}'`
do
#echo $line
line=${line/udp6 0.0.0.0:/};
line=${line/udp 0.0.0.0:/};
line=${line/0.0.0.0:/};
line=${line/127.0.0.1:/};
line=${line/udp6 /};
line=${line/udp /};
#line=${line/:::/};
line=${line// /|};
line=${line// /};
udp_port="$udp_port$line,"
done
udp_port=${udp_port//|,/,};
IFS=$IFS_old
udp_port=${udp_port::-1}
#echo $tcp_port
OLD_IFS=$IFS
IFS=$OLD_IFS
############获取开启的UDP端口#####################
echo "{'tcp_port':'$tcp_port','udp_port':'$udp_port'}"