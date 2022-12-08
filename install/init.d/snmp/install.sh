#!/bin/sh
#yum install perl-ExtUtils-MakeMaker package libperl-dev
#isContinue='y'
#echo "1"
#read -p 'continue?[y/n]' isContinue
#echo ${isContinue}
cd /opt/snmp
chmod a+x *.sh
tar zxvf net-snmp-5.7.3.tar.gz
cd net-snmp-5.7.3
#--with-mib-modules='ucd-snmp/diskio ip-mib/ipv4InterfaceTable'
./configure --prefix=/usr/local/snmp --enable-mfd-rewrites --with-default-snmp-version="3" --with-mib-modules=ucd-snmp/diskio
#echo -e "\n"
make clean
make
make install
mkdir /usr/local/snmp/etc
cp /opt/snmp/snmpd.conf /usr/local/snmp/etc/
echo "PATH=/usr/local/snmp/bin:/usr/local/snmp/sbin:$PATH" >> /etc/profile
source /etc/profile
net-snmp-config --create-snmpv3-user -ro -A Jg123456jk -a MD5 -X jg123456jk -x DES snmpv3
echo "/usr/local/snmp/sbin/snmpd -c /usr/local/snmp/etc/snmpd.conf &" >> /etc/rc.local
source /etc/rc.local

#yum install python-devel python3-devel libevent-devel
#easy_install gevent
#iptables -A INPUT -p udp -m udp -s 192.168.2.133 --dport 161 -j ACCEPT
#firewall-cmd --permanent --add-rich-rule="rule family="ipv4" source address="192.168.2.133" port protocol="udp" port="161" accept"
#firewall-cmd --reload

#vi /etc/sysconfig/iptables

#-A INPUT -m state --state NEW -m udp  -s 192.168.2.133 -p udp --dport 161 -j ACCEPT
#-A INPUT -m state --state NEW -m udp  -s 192.168.2.133 -p udp --dport 162 -j ACCEPT