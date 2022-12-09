# 金鼓运维管理系统

1、介绍
<br>
金鼓运维管理系统是金鼓公司的自研产品，内部使用，开源，主要具备监测、配置及告警功能。
<p>
2、系统架构
<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/xtjg.png"></p>
<p>
3、演示
<br>
演示地址：http://47.104.96.84:8013/<br>
演示账号：关注微信公众号后，输入ywdemo获取演示账号<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/gzh.png"></p>
<p>
4、安装<br>
环境：CensOS64 7.4及 以上（需要服务器能访问外网）<br>
1)服务器在线安装<br>
事前准备（安装 wget、git）<br>
注意：双斜杠//后的内容不要复制输入<br>
yum install -y wget git<br>
git clone https://gitee.com/jinguc/eoms.git<br>
cd eoms/install<br>
chmod 755  *.sh<br>

2)本地下载ZIP安装包后上传服务器安装<br>
安装包下载指引<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/download_zip.png"></p>
事前准备（安装 wget、unzip）<br>
yum install -y wget unzip<br>
进入压缩包同目录下解压安装包<br>
unzip eoms-master.zip<br>
cd eoms-master/install<br>
chmod 755  *.sh<br>

一键安装服务端:<br>
#全量安装，包括apache+php+mysql<br>
注：数据库的数据位置默认为安装目录/usr/local/mysql下的 data 目录<br>
      mysql数据库的默认 root 密码为Jingu.com<br>
      Apache的站点目录为/home/data/www<br>
./install_server.sh<br>

一键安装客户端：<br>
./install_agent.sh<br>

一键升级服务端:<br>
./upgrade_server.sh<br>

一键升级客户端:<br>
./upgrade_agent.sh<br>
<p>
5、使用说明<br>
关注微信视频号，收看视频讲解<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/sph.png" width="348"></p>
<p>
6、开发者群<br>
关注微信公众号后，输入yw获取微信群二维码进群，技术交流<br>
<p style="align:left;"><img src="https://www.jinguc.com/oms/img/kfq.png"></p>
<p>
7、常见问题<br>
1）如获取不到主机信息，请检查snmp服务是否正常启动<br>


