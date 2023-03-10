# Copyright (C) 2013 - 2022 Jingu <yw@jinguc.com>
# 
#
# Website:  https://www.jinguc.com
# Github:   

#Pre-installation snmp
snmp_preinstall_settings(){
  echo ""   
}
#Install snmp
install_snmp(){
    #PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
    #export PATH
    if check_sys packageManager apt; then
      sudo apt-get install snmpd snmp
    elif check_sys packageManager yum; then
      yum -y remove perl-ExtUtils-MakeMaker package libperl-dev bc sysstat net-snmp net-snmp-utils
      yum -y install perl-ExtUtils-MakeMaker package libperl-dev bc sysstat net-snmp net-snmp-utils
    fi
    
    
    #cd ${cur_dir}/software/
    #download_file  "${snmp_filename}.tar.gz" "${snmp_filename_url}"
    #tar zxvf ${snmp_filename}.tar.gz
    #cd ${snmp_filename}
    #./configure --prefix=/usr/local/snmp --enable-mfd-rewrites --with-default-snmp-version="3" --with-sys-contact="localhost,E_mail:localhost@localhost.com " --with-sys-location="China" --with-logfile="/var/log/snmpd.log" --with-persistent-directory="/var/net-snmp" --with-mib-modules=ucd-snmp/diskio
    #make
    #make install
    #mkdir /usr/local/snmp/etc
    if [ ! -d "/etc/snmp" ];then
      mkdir /etc/snmp
    fi
    cp -rp ${cur_dir}/conf/snmpd.conf /etc/snmp/
    cp -rp ${cur_dir}/init.d/snmp /opt/
    chmod a+x /opt/snmp/process
    #echo "PATH=/usr/local/snmp/bin:/usr/local/snmp/sbin:$PATH" >> /etc/profile
    #source /etc/profile
    #net-snmp-config --create-snmpv3-user -ro -A Jg123456jk -a MD5 -X jg123456jk -x DES snmpv3
    if check_sys packageManager apt; then
        sudo systemctl enable snmpd.service
        service snmpd restart
    elif check_sys packageManager yum; then
        systemctl enable snmpd.service
        systemctl restart snmpd
    fi
    #/usr/local/snmp/sbin/snmpd -c /usr/local/snmp/share/snmp/snmpd.conf
    n=$(iptables -nL | grep 161 | wc -l)
    
    if [ $n -eq 0 ]; then
      iptables -I INPUT -p udp --dport 161 -j ACCEPT
    fi
    cp -rp ${cur_dir}/conf/snmpd.ini /etc/supervisord.d
    systemctl restart supervisord
}