# Copyright (C) 2013 - 2022 Jingu <yw@jinguc.com>
# 
#
# Website:  https://www.jinguc.com
# Github:   

#Pre-installation supervisord
supervisord_preinstall_settings(){
    echo ""
}
#Install snmp
install_supervisord(){
    yum -y remove supervisor
    yum -y install supervisor
    systemctl enable supervisord
    systemctl start supervisord
}