
# 金鼓运维管理系统

# 1、介绍
金鼓运维管理系统是金鼓公司的自研产品，内部使用，开源，主要具备监测、配置及告警功能。

# 2、系统架构
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/xtjg.png"></p>

# 3、演示

演示地址：https://eoms.jinguc.com
演示账号：关注微信公众号后，输入ywdemo获取演示账号

<img src="https://www.jinguc.com/oms/img/gzh.png">

# 4、版本更新日志

升级前必看：<a href="CHANGELOG.md">CHANGELOG.md</a>

# 5、安装与卸载

## 安装

<a href="#5使用说明">注：详看视频讲解“金鼓运维管理系统的安装”(关注微信视频号，收看视频讲解)</a>
环境：CensOS64 7.4及以上（需要服务器能访问外网）

### 事前准备（安装 wget、git、unzip）

在安装前，需要下载安装包，有两种方式进行下载

#### 1）git拉取下载安装包

第一步：安装wget、unzip和git软件（如果操作系统已经支持，可跳过）

```
yum install -y wget git unzip
```

第二步：git拉取

```
git clone https://gitee.com/jinguc/eoms.git
```

第三步：切换到安装目录并赋值执行（X）权限

```
cd eoms/install
chmod 755  *.sh
```


#### 2）手动下载ZIP安装包

第一步：按照如下图示进行下载

<img src="https://www.jinguc.com/oms/img/download_zip.png">

第二步：解压安装包

```
unzip eoms-master.zip
```

第三步：切换到安装目录并赋值执行（X）权限

```
cd eoms-master/install
chmod 755  *.sh
```


### 开始安装

#### 第一步：一键安装服务端

#方式一：全量安装
(包括运维管理系统+apache+php+mysql。在没有apache+php+mysql环境时，应采用本方式安装)

> 注：数据库的数据位置默认为安装目录/usr/local/mysql下的 data 目录
> mysql数据库的默认 root 密码为Jingu.com
> Apache的站点目录为/home/data/www

```
./install_server.sh //运行脚本
```


#方式二：仅安装运维管理系统
(已经具有apache+php+mysql环境时，应采用本方式安装)

> 注意：
> 系统运行环境要求：（如运行环境版本不一致可能会导致系统程序不兼容）

Apache 2.4
PHP 7.4（PHP需要开启snmp扩展）
MySql 5.7

> 注意：
> 如果Apache虚拟站点配置文件只有httpd.conf时,请将以下代码复制到httpd.conf文件中，然后保存并重启Apache。

```
Listen 8013
<VirtualHost _default_:8013>
ServerName localhost:8013
DocumentRoot /home/data/www/public
<Directory /home/data/www/public>
    SetOutputFilter DEFLATE
    Options FollowSymLinks
    AllowOverride All
    Order Deny,Allow
    Allow from All
    DirectoryIndex index.php index.html index.htm
</Directory>
</VirtualHost>
```

```
./install_server.sh www  //运行脚本
```


#### 第二步：一键安装客户端

```
./install_agent.sh  //运行脚本
```


#### 第三步：安装APP

关注微信视频号，收看安装视频讲解

<img src="https://www.jinguc.com/oms/img/sph.png" width="348">

## 升级

一键升级服务端:

```
./upgrade_server.sh  //运行脚本 
```

一键升级客户端:

```
./upgrade_agent.sh  //运行脚本
```

## 卸载

一键卸载服务端:

```
./uninstall.sh server  //运行脚本
```


一键卸载客户端:

```
./uninstall.sh agent  //运行脚本
```



# 6、使用说明

关注微信视频号，收看视频讲解

<img src="https://www.jinguc.com/oms/img/sph.png" width="348">



# 7、开发者群

关注微信公众号后，输入yw获取微信群二维码进群，技术交流

<img src="https://www.jinguc.com/oms/img/kfq.png">


# 8、常见问题

1）如获取不到主机信息，请检查snmp服务是否正常启动

