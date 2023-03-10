# Copyright (C) 2013 - 2022 Jingu <yw@jinguc.com>
# 
#
# Website:  https://www.jinguc.com
# Github:   

#Pre-installation py-eoms
py-eoms_preinstall_settings(){
    echo ""
}
#Install py-eoms
install_py-eoms(){
    cd /opt/snmp
    tar xvf py-eoms.tar
    cp -rp ${cur_dir}/conf/py-eoms.ini /etc/supervisord.d
    systemctl restart supervisord
    supervisorctl restart py-eoms
    n=$(iptables -nL | grep 888 | wc -l)
    if [ $n -eq 0 ]; then
        iptables -I INPUT -p tcp --dport 888 -j ACCEPT
    fi
}