#!/bin/sh
type=$1
port=$2
#If more than one IP or port Please '|' separated    
#ip="172.31.175.211|127.0.0.1"  #我们服务器是双线接入，所以有2个IP    
#port="80|443|8001|8003|3306"                #我这里是监控80和443端口   
#netstat -an |grep 'ESTABLISHED' |grep ${type} |grep ${port} |wc -l 
#webconn=`netstat -nt |grep 'ESTABLISHED' |grep ${type} |grep ${port} |wc -l` 
webestab=`netstat -nt |grep 'ESTABLISHED' |grep ${type} |grep ${port} |wc -l`
websyn=`netstat -nt |grep 'SYN_RECV' |grep ${type} |grep ${port} |wc -l`    
weback=`netstat -nt |grep 'LAST_ACK' |grep ${type} |grep ${port} |wc -l`    
webwait=`netstat -nt |grep 'TIME_WAIT' |grep ${type} |grep ${port} |wc -l` 
tcp_total=$((webestab+websyn+weback+webwait))
echo "{'TCP_TOTAL':'$tcp_total','ESTABLISHED':'$webestab','SYN_RECV':'$websyn','LAST_ACK':'$weback','TIME_WAIT':'$webwait'}"