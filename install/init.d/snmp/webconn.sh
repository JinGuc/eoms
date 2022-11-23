#!/bin/sh
port=$1
#If more than one IP or port Please '|' separated    
#ip="172.31.175.211|127.0.0.1"  #���Ƿ�������˫�߽��룬������2��IP    
#port="80|443|8001|8003|3306"                #�������Ǽ��80��443�˿�   
#netstat -an |grep 'ESTABLISHED' |grep 'tcp' |wc -l 
#webconn=`netstat -nt |grep ESTABLISHED |grep 'tcp' |wc -l` 
webestab=`netstat -nt |grep ESTABLISHED | grep 'tcp' |wc -l`
websyn=`netstat -nt |grep SYN_RECV |grep 'tcp' |wc -l`    
weback=`netstat -nt |grep LAST_ACK |grep 'tcp' |wc -l`    
webwait=`netstat -nt |grep TIME_WAIT |grep 'tcp' |wc -l` 
tcp_total=$((webestab+websyn+weback+webwait))
echo "{'TCP_TOTAL':'$tcp_total','ESTABLISHED':'$webestab','SYN_RECV':'$websyn','LAST_ACK':'$weback','TIME_WAIT':'$webwait'}"