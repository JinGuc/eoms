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

#Pre-installation php
php_preinstall_settings(){
    if [ "${apache}" == "do_not_install" ]; then
        php="do_not_install"
    else
        display_menu php 1
    fi
}

#Intall PHP
install_php(){
    pnum=$(pgrep php | wc -l)
    findserver=$(whereis php |awk -F : '{print $2}' | sed '/^$/d')
    if [ $pnum -gt 0 ] || [ -n "$findserver" ]; then
    _info "该主机已经存在PHP,本次安装退出........"
    exit 0
    fi
    local openssl_version=$(openssl version -v)
    local major_version=$(echo ${openssl_version} | awk '{print $2}' | grep -oE "[0-9.]+")
    is_64bit && with_libdir="--with-libdir=lib64" || with_libdir=""
    php_configure_args="
    --prefix=${php_location} \
    --with-apxs2=${apache_location}/bin/apxs \
    --with-config-file-path=${php_location}/etc \
    --with-config-file-scan-dir=${php_location}/php.d \
    --with-pcre-jit \
    --with-imap \
    --with-kerberos \
    --with-imap-ssl \
    --with-openssl \
    --with-snmp \
    ${with_libdir} \
    --enable-mysqlnd \
    --with-mysqli=mysqlnd \
    --with-mysql-sock=/tmp/mysql.sock \
    --with-pdo-mysql=mysqlnd \
    --enable-gd \
    --with-jpeg \
    --with-freetype \
    --with-zlib \
    --with-bz2 \
    --with-curl=/usr \
    --with-gettext \
    --with-mhash \
    --with-readline \
    --with-xsl \
    --enable-zend-test \
    --enable-bcmath \
    --enable-calendar \
    --enable-dba \
    --enable-exif \
    --enable-gd-jis-conv \
    --enable-intl \
    --enable-mbstring \
    --enable-pcntl \
    --enable-shmop \
    --enable-soap \
    --enable-sockets \
    --with-zip \
    ${disable_fileinfo}"

    touch /usr/lib64/pkgconfig/libjpeg.pc
    echo "
    prefix=/usr
    exec_prefix=/usr
    libdir=/usr/lib64
    includedir=/usr/include

    Name: libjpeg
    Description: A SIMD-accelerated JPEG codec that provides the libjpeg API
    Version: 1.2.90
    Libs: -L${libdir} -ljpeg
    Cflags: -I${includedir}
    " > /usr/lib64/pkgconfig/libjpeg.pc

    yum install -y freetype freetype-devel
    #Install PHP depends
    install_php_depends

    cd ${cur_dir}/software/
    wget ${download_root_url}/oniguruma-6.8.2-1.el7.x86_64.rpm
    wget ${download_root_url}/oniguruma-devel-6.8.2-1.el7.x86_64.rpm
    rpm -ivh oniguruma*
    if [ "${php}" == "${php7_4_filename}" ]; then
        download_file  "${php7_4_filename}.tar.gz" "${php7_4_filename_url}"
        tar zxf ${php7_4_filename}.tar.gz
        cd ${php7_4_filename}
        # Fixed a libenchant-2 error in PHP 7.4 for Debian or Ubuntu
        if dpkg -l 2>/dev/null | grep -q "libenchant-2-dev"; then
            patch -p1 < ${cur_dir}/src/remove-deprecated-call-and-deprecate-function.patch
            patch -p1 < ${cur_dir}/src/use-libenchant-2-when-available.patch
            ./buildconf -f
        fi
        # Fixed build with OpenSSL 3.0 with disabling useless RSA_SSLV23_PADDING
        if version_ge ${major_version} 3.0.0; then
            patch -p1 < ${cur_dir}/src/minimal_fix_for_openssl_3.0_php7.4.patch
        fi
        # Fixed PHP extension snmp build without DES
        patch -p1 < ${cur_dir}/src/php-7.4-snmp.patch
    elif [ "${php}" == "${php8_0_filename}" ]; then
        download_file  "${php8_0_filename}.tar.gz" "${php8_0_filename_url}"
        tar zxf ${php8_0_filename}.tar.gz
        cd ${php8_0_filename}
        # Fixed build with OpenSSL 3.0 with disabling useless RSA_SSLV23_PADDING
        if version_ge ${major_version} 3.0.0; then
            patch -p1 < ${cur_dir}/src/minimal_fix_for_openssl_3.0_php8.0.patch
        fi
    elif [ "${php}" == "${php8_1_filename}" ]; then
        download_file  "${php8_1_filename}.tar.gz" "${php8_1_filename_url}"
        tar zxf ${php8_1_filename}.tar.gz
        cd ${php8_1_filename}
    fi

    if ! grep -q -w -E "^/usr/local/lib64" /etc/ld.so.conf.d/*.conf && [ -d "/usr/local/lib64" ]; then
        echo "/usr/local/lib64" > /etc/ld.so.conf.d/locallib64.conf
    fi
    ldconfig
    error_detect "./configure ${php_configure_args}"
    error_detect "make clean"
    error_detect "parallel_make"
    error_detect "make install"

    mkdir -p ${php_location}/{etc,php.d}
    cp -f ${cur_dir}/conf/php.ini ${php_location}/etc/php.ini
    config_php
}

config_php(){
    rm -f /etc/php.ini /usr/bin/php /usr/bin/php-cgi /usr/bin/php-config /usr/bin/phpize
    ln -s ${php_location}/etc/php.ini /etc/php.ini
    ln -s ${php_location}/bin/php /usr/bin/
    ln -s ${php_location}/bin/php-cgi /usr/bin/
    ln -s ${php_location}/bin/php-config /usr/bin/
    ln -s ${php_location}/bin/phpize /usr/bin/
    cat > ${php_location}/php.d/opcache.ini<<EOF
[opcache]
zend_extension=opcache.so
opcache.enable_cli=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.save_comments=1
EOF

    #cp -f ${cur_dir}/conf/ocp.php ${web_root_dir}
    #cp -f ${cur_dir}/conf/jquery.js ${web_root_dir}
    #cp -f ${cur_dir}/conf/phpinfo.php ${web_root_dir}
    #wget -O ${web_root_dir}/p.php ${x_prober_url} > /dev/null 2>&1
    if [ $? -ne 0 ]; then
        _warn "Download X-Prober failed, please manually download from ${x_prober_url} if necessary."
    fi
    chown -R apache.apache ${web_root_dir}

    if [[ -d "${mysql_data_location}" || -d "${mariadb_data_location}" ]]; then
        sock_location="/tmp/mysql.sock"
        sed -i "s#mysql.default_socket.*#mysql.default_socket = ${sock_location}#" ${php_location}/etc/php.ini
        sed -i "s#mysqli.default_socket.*#mysqli.default_socket = ${sock_location}#" ${php_location}/etc/php.ini
        sed -i "s#pdo_mysql.default_socket.*#pdo_mysql.default_socket = ${sock_location}#" ${php_location}/etc/php.ini
    fi
    if [[ -d "${apache_location}" ]]; then
        sed -i "s@AddType\(.*\)Z@AddType\1Z\n    AddType application/x-httpd-php .php .phtml\n    AddType appication/x-httpd-php-source .phps@" ${apache_location}/conf/httpd.conf
    fi

}
