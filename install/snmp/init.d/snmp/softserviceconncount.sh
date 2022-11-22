#!/bin/bash
servername=$1
pids=$(ps -aux|grep ${servername}| awk '{print $2}')
let webconn=0
for pid in $pids
do
 webconn_=`netstat -antup|grep ${pid} |grep -E 'tcp|udp' |wc -l` 
 #webconn_=`lsof -i:${pid} | wc -l`
 #echo $webconn_
 webconn=$(($webconn+$webconn_))
done 
echo "{'CONNTOTAL':'$webconn'}"