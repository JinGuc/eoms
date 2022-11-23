#!/bin/bash
#定义了一个名为getBytes的函数
getBytes() { 
    #此条命令的目的是获取承载系统默认路由的网卡名称。 
    cardName=$(ip route | grep default | awk -F ' ' {'print $5'})
    #获取当前系统已接收的字节数 
    prevRxBytes=$(ip -s link ls $cardName | tail -n 3 | head -n 1 | awk {'print $1'}) 
    #获取当前系统已发送的字节数 
    prevTxBytes=$(ip -s link ls $cardName | tail -n 1 | awk {'print $1'}) 
    #让shell睡眠1秒 
    sleep 1 
    #再次获取当前系统已接收的字节数 
    curRxBytes=$(ip -s link ls $cardName | tail -n 3 | head -n 1 | awk {'print $1'}) 
    #获取实际上行(in)流量，公式为当前已接收字节数-1秒前已接收字节数，得到的数字以字节为单位， #除以1次1024后单位为Kbytes，再除以1次1024后单位为Mbytes。 
    realRxBytes=$(((curRxBytes - prevRxBytes)))
    #再次获取当前系统已发送的字节数 
    curTxBytes=$(ip -s link ls $cardName | tail -n 1 | awk {'print $1'})
    #获取实际下行(out)流量，公式和上行流量类似 
    realTxBytes=$(((curTxBytes - prevTxBytes))) 
    #向屏幕输出结果 
    echo "{'in':"${realRxBytes},"'out':"${realTxBytes}"}"
}
getBytes
