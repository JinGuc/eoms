#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH

cur_dir=$(pwd)


yum -y install perl-ExtUtils-MakeMaker package libperl-dev bc sysstat

cd ${cur_dir}/src
tar zxvf net-snmp-5.7.3.tar.gz
cd net-snmp-5.7.3
./configure --prefix=/usr/local/snmp --enable-mfd-rewrites --with-default-snmp-version="3" --with-sys-contact="localhost,E_mail:localhost@localhost.com " --with-sys-location="China" --with-logfile="/var/log/snmpd.log" --with-persistent-directory="/var/net-snmp" --with-mib-modules=ucd-snmp/diskio
make
make install
# mkdir /usr/local/snmp/etc
mkdir /etc/snmpd/
cp -rp ${cur_dir}/conf/snmpd.conf /etc/snmpd/
cp -rp ${cur_dir}/init.d/snmp /opt/
echo "PATH=/usr/local/snmp/bin:/usr/local/snmp/sbin:$PATH" >> /etc/profile
source /etc/profile
net-snmp-config --create-snmpv3-user -ro -A Jg123456jk -a MD5 -X jg123456jk -x DES snmpv3
systemctl restart snmpd
iptables -A INPUT -p udp --dport 161 -j ACCEPT
# cp -rp ${cur_dir}/conf/snmpd.ini /etc/supervisord.d
# systemctl restart supervisord