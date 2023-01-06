# 金鼓运维管理系统

# 1、介绍
<br>
金鼓运维管理系统是金鼓公司的自研产品，内部使用，开源，主要具备监测、配置及告警功能。

# 2、系统架构
<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/xtjg.png"></p>

# 3、演示
<br>
演示地址：https://eoms.jinguc.com<br>
演示账号：关注微信公众号后，输入ywdemo获取演示账号<br>

<p style="align:left;"><img src="https://www.jinguc.com/oms/img/gzh.png"></p>

# 4、版本更新日志
升级前必看：<a href="CHANGELOG.md">CHANGELOG.md</a>

# 5、安装与卸载
## 安装
<em><a href="#5使用说明">注：详看视频讲解“金鼓运维管理系统的安装”(关注微信视频号，收看视频讲解)</a></em><br>
环境：CensOS64 7.4及以上（需要服务器能访问外网）<br>

### 事前准备（安装 wget、git、unzip）<br>
在安装前，需要下载安装包，有两种方式进行下载
#### 1）git拉取下载安装包<br>
第一步：安装wget、unzip和git软件（如果操作系统已经支持，可跳过）<br>
yum install -y wget git unzip<br>
第二步：git拉取<br>
git clone https://gitee.com/jinguc/eoms.git<br>
第三步：切换到安装目录并赋值执行（X）权限<br>
cd eoms/install<br>
chmod 755  *.sh<br>

#### 2）手动下载ZIP安装包<br>
第一步：按照如下图示进行下载<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/download_zip.png"></p>

第二步：解压安装包<br>
unzip eoms-master.zip<br>
第三步：切换到安装目录并赋值执行（X）权限<br>
cd eoms-master/install<br>
chmod 755  *.sh<br>

### 开始安装
#### 第一步：一键安装服务端<br>
#方式一：全量安装(包括运维管理系统+apache+php+mysql。在没有apache+php+mysql环境时，应采用本方式安装)<br>
注：数据库的数据位置默认为安装目录/usr/local/mysql下的 data 目录<br>
      mysql数据库的默认 root 密码为Jingu.com<br>
      Apache的站点目录为/home/data/www<br>
运行脚本 ./install_server.sh<br>

#方式二：仅安装运维管理系统(已经具有apache+php+mysql环境时，应采用本方式安装)<br>
注：系统运行环境要求：（如运行环境版本不一致可能会导致系统程序不兼容）<br>
Apache 2.4<br>
PHP 7.4（PHP需要开启snmp扩展）<br>
MySql 5.7<br>
注：如果Apache虚拟站点配置文件只有httpd.conf时,请将以下代码复制到httpd.conf文件中，然后保存并重启Apache。<br>
Listen 8013<br>
< VirtualHost _default_:8013><br>
ServerName localhost:8013<br>
DocumentRoot /home/data/www/public<br>
<Directory /home/data/www/public><br>
    SetOutputFilter DEFLATE<br>
    Options FollowSymLinks<br>
    AllowOverride All<br>
    Order Deny,Allow<br>
    Allow from All<br>
    DirectoryIndex index.php index.html index.htm<br>
<\/Directory><br>
<\/VirtualHost><br>
运行脚本 ./install_server.sh www<br>

#### 第二步：一键安装客户端<br>
运行脚本 ./install_agent.sh<br>

#### 第三步：安装APP<br>
关注微信视频号，收看安装视频讲解<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/sph.png" width="348"></p>

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

# 6、使用说明
关注微信视频号，收看视频讲解<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/sph.png" width="348"></p>
<p>

# 7、开发者群
关注微信公众号后，输入yw获取微信群二维码进群，技术交流<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/kfq.png"></p>

# 8、常见问题

1）如获取不到主机信息，请检查snmp服务是否正常启动<br> 

