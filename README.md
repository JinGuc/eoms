# 运维管理系统

1、介绍
该运维管理系统是金鼓公司的自研产品，内部使用，开源，主要具备监测、配置及告警功能。
<p>
2、系统架构
<img src="https://www.jinguc.com/oms/img/xtjg.png">
<p>
3、演示
演示地址：http://47.104.96.84:8013/
演示账号：关注微信公众号后，输入ywdemo获取演示账号
<img src="https://www.jinguc.com/oms/img/gzh.png">
<p>
4、安装
环境：CensOS64 7.4以上
事前准备（安装 wget、git）
注意：双斜杠//后的内容不要复制输入
yum -y install wget git      // for Amazon Linux/CentOS
apt-get -y install wget git  // for Debian/Ubuntu：
git clone https://gitee.com/jinguc/eoms.git
cd eoms/install
chmod 755 *.sh
一键安装服务端
#全量安装，包括apache+php+mysql
注：数据库的数据位置默认为安装目录/usr/local/mysql下的 data 目录
      数据库的默认 root 密码为Jingu.com
      Apache的站点目录为/home/data/www
./install_server.sh
一键安装客户端：
./install_agent.sh

等待安装完成
<p>
5、使用说明
关注微信视频号，收看视频讲解
<img src="https://www.jinguc.com/oms/img/sph.png">
<p>
6、开发者群
关注微信公众号后，输入yw获取微信群二维码进群，技术交流
<img src="https://www.jinguc.com/oms/img/kfq.png">
<p>
7、常见问题

