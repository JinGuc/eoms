#!/bin/bash
############获取硬盘数量#####################
i=0
Disk=""
IFS_old=$IFS
IFS=$'\n'
for line in `fdisk -l |grep '/dev' |grep 'GB' | grep -E 'vd|hd|sd|vg' |awk -F , '{print $1}' | sed '/^$/d'`
do
line=${line/磁盘/Disk};
line=${line/Disk/};
line=${line/: /:};
line=${line/GB/};
line=${line/：/:};
line=${line/ /};
line_=${line/:/,};
#size_=`echo "  *$line_ " | grep -oP '\d*\.\d+'`
#echo $line_
line__=`echo $line_ |awk -F, '{print $1}'`
strA=`cat /etc/fstab| xargs echo -n`
strB="a"
#echo $line
#if [[ $strA =~ $line__ || $line =~ $strB ]]
#then
Disk="$Disk$line@"
Disk=${Disk/ /};
#else
#  continue
#fi
done
IFS="$OLD_IFS"
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
if [ -z "$totalsize" ] 
then
totalsize=1000
fi
################获取磁盘剩余总容量######################
avasize=`df |grep ${disk_name}| awk '{print $4}' |sed '/^\s*$/d' | awk '{sum+=$1} END {print sum}'`
if [ -z "$avasize" ] 
then
avasize=1000
fi
disk_info_name_avasize_percent=`echo "scale=2; $avasize*100/$totalsize" | bc` 
total_p=100
disk_info_name_used_percent=`echo "scale=2; $total_p-$disk_info_name_avasize_percent" | bc` 
#echo $disk_info_name_used_percent
################获取磁盘总indoes######################
totalindoessize=`df -i |grep ${disk_name}| awk '{print $2}' |sed '/^\s*$/d' | awk '{sum+=$1} END {print sum}'`
if [ -z "$totalindoessize" ] 
then
totalindoessize=1000
fi
################获取磁盘USED-indoes######################
usedindoessize=`df -i |grep ${disk_name}| awk '{print $3}' |sed '/^\s*$/d' | awk '{sum+=$1} END {print sum}'`
if [ -z "$usedindoessize" ] 
then
usedindoessize=0
fi
disk_info_name_indoes_used_percent=`echo "scale=2; $usedindoessize*100/$totalindoessize" | bc`
Disks="$Disks$s:$disk_info_name_used_percent:$disk_info_name_indoes_used_percent@"
done
Disks=${Disks::-1}

IFS_old=$IFS
IFS=$'\n'
Filesystem=""
for line in `df | grep -v 'Use%' |awk  '{print $1,$2,$3,$5,$6}'`
do
line=${line// /:};
line=${line//%/};
echo $line
if [ ! -n "$Filesystem" ];then
Filesystem=$line
else
Filesystem=$Filesystem@$line
fi
done

echo "{'disks':'$Disks','Filesystem':'$Filesystem'}"
