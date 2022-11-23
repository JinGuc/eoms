#!/bin/bash
IFS_old=$IFS
IFS=$'\n'
servername=$1
ctrl=$2
systemctl ${ctrl} ${servername}
str1="Active"
str2="Memory"
status=""
memoryused=""
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
echo "{'status':'$status','memoryused':'$memoryused'}"