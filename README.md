# 金鼓运维管理系统

# 1、介绍
<br>
金鼓运维管理系统是金鼓公司的自研产品，内部使用，开源，主要具备监测、配置及告警功能。

# 2、系统架构
<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/xtjg.png"></p>

# 3、演示
<br>
演示地址：http://47.104.96.84:8013/<br>
演示账号：关注微信公众号后，输入ywdemo获取演示账号<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/gzh.png"></p>

# 4、安装与卸载
## 安装
<em><a href="#5使用说明">注：详看视频讲解“金鼓运维管理系统的安装”(关注微信视频号，收看视频讲解)</a></em><br>
环境：CensOS64 7.4及以上（需要服务器能访问外网）<br>

### 1）服务器在线安装<br>
事前准备（安装 wget、git）<br>
注意：双斜杠//后的内容不要复制输入<br>
yum install -y wget git<br>
git clone https://gitee.com/jinguc/eoms.git<br>
cd eoms/install<br>
chmod 755  *.sh<br>

### 2）本地下载ZIP安装包后上传服务器安装<br>
安装包下载指引<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/download_zip.png"></p>
事前准备（安装 wget、unzip）<br>
yum install -y wget unzip<br>
进入压缩包同目录下解压安装包<br>
unzip eoms-master.zip<br>
cd eoms-master/install<br>
chmod 755  *.sh<br>

### 一键安装服务端:<br>
#全量安装，包括apache+php+mysql<br>
注：数据库的数据位置默认为安装目录/usr/local/mysql下的 data 目录<br>
      mysql数据库的默认 root 密码为Jingu.com<br>
      Apache的站点目录为/home/data/www<br>
运行脚本 ./install_server.sh<br>

一键安装金鼓运维管理系统：<br>
#(仅安装金鼓运维管理系统,适用于已经安装过Apahce、PHP、MySql的服务器)
注：系统运行环境要求：（如运行环境版本不一致可能会导致系统程序不兼容）<br>
Apache 2.4<br>
PHP 7.4（PHP需要开启snmp扩展）<br>
MySql 5.7<br>
注：如果Apache虚拟站点配置文件只有httpd.conf时,请将以下代码复制到httpd.conf文件中，然后保存并重启Apache。<br>
Listen 8013<br>
<VirtualHost _default_:8013><br>
ServerName localhost:8013<br>
DocumentRoot /home/data/www/public<br>
<Directory /home/data/www/public><br>
    SetOutputFilter DEFLATE<br>
    Options FollowSymLinks<br>
    AllowOverride All<br>
    Order Deny,Allow<br>
    Allow from All<br>
    DirectoryIndex index.php index.html index.htm<br>
</Directory><br>
</VirtualHost><br>
运行脚本 ./install_server.sh www<br>

### 一键安装客户端：<br>
运行脚本 ./install_agent.sh<br>

## 升级
一键升级服务端:<br>
运行脚本 ./upgrade_server.sh<br>

一键升级客户端:<br>
运行脚本 ./upgrade_agent.sh<br>
## 卸载
一键卸载服务端:<br>
运行脚本 ./uninstall.sh server<br>

一键卸载客户端:<br>
运行脚本 ./uninstall.sh agent<br>
<p>

# 5、使用说明
关注微信视频号，收看视频讲解<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/sph.png" width="348"></p>
<p>

# 6、开发者群
关注微信公众号后，输入yw获取微信群二维码进群，技术交流<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/kfq.png"></p>

# 7、常见问题
## 1）如获取不到主机信息，请检查snmp服务是否正常启动<br>


