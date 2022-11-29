# Copyright (C) 2013 - 2022 Jingu <yw@jinguc.com>
# 
# This file is part of the LAMP script.
#
# LAMP is a powerful bash script for the installation of 
# Apache + PHP + MySQL/MariaDB and so on.
# You can install Apache + PHP + MySQL/MariaDB in an very easy way.
# Just need to input numbers to choose what you want to install before installation.
# And all things will be done in a few minutes.
#
# Website:  https://www.jinguc.com
# Github:   

#upgrade py-eoms
upgrade_py-eoms(){
    cp -rp ${cur_dir}/conf/snmpd.conf /etc/snmp/
    cp -rp ${cur_dir}/init.d/snmp /opt/
    chmod a+x /opt/snmp/process
    systemctl restart supervisord
}