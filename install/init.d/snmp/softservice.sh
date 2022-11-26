#!/bin/bash
IFS_old=$IFS
IFS=$'\n'
servername=$1
str1="Active"
str2="Memory"
status=""
memoryused=""
m=0
danwei="M"
status_="running"
for line in `systemctl status ${servername}`
do
if [[ $line =~ $str1 ]] ;then
line=${line/   /};
line=${line/Active: active /};
status=$line
fi
if [[ $line =~ $str2 ]] ;then
line=${line/   /};
line=${line/Memory: /};
memoryused=$line
fi
IFS=$IFS_old
done
#echo $servername
#echo $status
pids=$(pgrep ${servername})
#echo $pids
let memused=0
let cpuused=0
let phymemused=0
lstart=""
for pid in $pids
do
  pidinfo=$(ps -e o pid,lstart|grep $pid)
  pidinfo=${pidinfo/$pid/};
  lstart=$(date -d "$pidinfo" +'%Y-%m-%d %H:%M:%S')
  #echo $lstart
  if [ ! -n "$status" ]; then
  status="running"
  fi
#echo $pid
memused_=$(cat /proc/$pid/status | grep 'VmRSS' | awk '{print $2}')
#memused_=$(pmap -x $pid | grep 'total'| awk '{print $4}')
#echo $memused_
if [ $memused_ -gt 0 ] 2>/dev/null ;then 
    memused=$(($memused+$memused_))
fi
#echo $memused
#echo $pid
cpuused_=$(top -b -n 1 -p $pid | sed '1,7d' |awk '{print $9}')
#echo $cpuused_
if [ -n "$cpuused_" ]; then 
     cpuused=`echo "scale=2; $cpuused+$cpuused_" | bc`
fi
#echo $cpuused
done
phymemused=$memused
memused=`echo "sclae=2; $memused/1024" | bc`
if [ $memused -ge 1024 ];then
    memused=`echo "sclae=2; $memused/1024" | bc`
    danwei="G"
fi
#echo $memused$danwei
#echo $status
if [ -z "$memoryused" ]; then
   memoryused=$memused$danwei
   #if [ $memused -gt 0 ];then
   #status="running"
   #fi
fi
if [[ $status =~ $status_ ]]
then
  m=m+1
else
  memoryused="0$daiwei"
fi
let memUsedPercent=0
phymem=`free | grep "Mem:" |awk '{print $2}'`
memUsedPercent=`awk 'BEGIN{printf"%.2f\n",('$phymemused'/'$phymem')*100}'`
FIND_STR="running"
if [[ $status == *$FIND_STR* ]]
then
    f=1
else
    f=0
fi
if [ $f -eq 0 ]  && [ $phymemused -gt 0 ];then
   status="running"
   else
   status="stop"
fi
echo "{'status':'$status','memoryused':'$memoryused','memUsedPercent':'$memUsedPercent','cpuused':'$cpuused','starttime':'$lstart'}"