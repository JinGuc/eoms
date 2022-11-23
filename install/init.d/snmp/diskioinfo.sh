#!/bin/bash
DiskIo=''
IFS_old=$IFS
IFS=$'\n'
num=5
for line in `iostat -d 1 1 | grep -E '^vd|^hd|^sd'`
do
IFS=$' '
info=''
for aline in $line
do
if [[ $aline == *vd*||$aline == *sd*||$aline == *hd* ]]; then
#echo $aline
lineI_=''
declare -a array
i=0
for lineI in `iostat -d -k 1 ${num} ${aline} | grep -E '^vd|^hd|^sd'|awk '{print $1,$2,$3,$4,$5,$6}'|awk 'BEGIN{x=0}{if(x==1){sum1=$2}{sum2=$3}{sum3=$4}{sum4=$5}{sum5=$6}}{x++} END {print sum1,sum2,sum3,sum4,sum5}'`
do
lineI_="$lineI_:$lineI"
let "i++"
done
declare -a array
i=0
for lineI in `iostat -x 1 ${num} ${aline} | grep -E '^vd|^hd|^sd'|awk '{print $10,$14}'|awk 'BEGIN{x=0}{if(x==1){sum1=$1}{sum2=$2}}{x++} END {print sum1,sum2}'`
do
array[$i]=$lineI
let "i++"
done
total_util=${array[0]}
#avg_util=`echo "scale=2; $total_util" | bc`
avg_util=$total_util
total_await=${array[1]}
#avg_await=`echo "scale=2; $total_await" | bc`
avg_await=$total_await
#echo $avg_util
oinfo="$avg_await:$avg_util"
info="$info$aline:$lineI_:$oinfo"
fi
done
#info="$info$oinfo"
info=${info/::/:};
#info=${info::-1}
IFS=$IFS_old
DiskIo="$DiskIo$info@"
done
IFS=$IFS_old
DiskIo=${DiskIo::-1}

#cIo=''
#IFS_old=$IFS
#IFS=$'\n'
#for line in `iostat -x 1 1 | grep -E '^vd|^hd' |awk '{print $1,$10,$14}'`
#do
#IFS=$' '
#info=''
#for aline in $line
#do
#info="$info$aline:"
#done
#info=${info::-1}
#IFS=$IFS_old
#cIo="$cIo$info@"
#done
#IFS=$IFS_old
#cIo=${cIo::-1}
#echo $cIo
echo "{'DiskIo':'$DiskIo'}"