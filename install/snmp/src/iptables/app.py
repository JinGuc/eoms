# coding: utf-8
import time, sys
from munch import DefaultMunch
from flask import Flask, request, g, abort, Response, stream_with_context
from flask_basicauth import BasicAuth
from gevent import pywsgi
from ast import literal_eval

if not 'class/' in sys.path:
    sys.path.insert(0, 'class/')

import public
from iptables import firewall_main
from mysql import Mysql
from apache import Apache
from nginx import Nginx
from ipcc import Ipcc
from redis import Redis
from docker import Docker
from snmp import Snmp
from server import Server
from php import Php
from phpfpm import Phpfpm
from servicectrl import Service_Ctrl

# 初始化Flask应用
app = Flask(__name__)

app.config["BASIC_AUTH_USERNAME"] = public.getConfig('auth', 'username')
app.config["BASIC_AUTH_PASSWORD"] = public.getConfig('auth', 'password')

app.config["BASIC_AUTH_FORCE"] = True
basic_auth = BasicAuth(app)

method_all = ['GET', 'POST']
method_get = ['GET']
method_post = ['POST']


@app.before_request
def request_check():
    if request.method not in ['GET', 'POST']: return abort(404)
    g.request_time = time.time()
    # 路由和URI长度过滤
    if len(request.path) > 256: return abort(403)
    if len(request.url) > 1024: return abort(403)
    pdata = request.form.to_dict()
    for k in pdata.keys():
        if len(k) > 48: return abort(403)
        if len(pdata[k]) > 256: return abort(403)


@app.route('/server/list', methods=method_post)
def server_list():
    """获取运行服务列表"""
    s = Server()
    result = s.info()
    return public.ReturnJson(True, result)


@app.route('/iptables/get_status', methods=method_post)
def iptables_get_firewall_status():
    """获取防火墙状态"""
    pdata = DefaultMunch.fromDict({})
    fw = firewall_main()
    if hasattr(fw, "get_firewall_status"):
        result = fw.get_firewall_status(pdata)
        return public.returnMsg(True, result)

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/set_status', methods=method_post)
def iptables_firewall_admin():
    """操作防火墙"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "firewall_admin"):
        result = fw.firewall_admin(pdata)
        return public.returnMsg(True, result)

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/get_rules_list', methods=method_post)
def iptables_get_rules_list():
    """获取防火墙端口规则"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "get_rules_list"):
        result = fw.get_rules_list(pdata)
        return public.returnMsg(True, result)

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/create_rules', methods=method_post)
def iptables_create_rules():
    """设置防火墙端口规则"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "create_rules"):
        result = fw.create_rules(pdata)
        return result

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/modify_rules', methods=method_post)
def iptables_modify_rules():
    """修改防火墙端口规则"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "modify_rules"):
        result = fw.modify_rules(pdata)
        return result

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/remove_rules', methods=method_post)
def iptables_remove_rules():
    """删除防火墙端口规则"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "remove_rules"):
        result = fw.remove_rules(pdata)
        return result

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/get_ip_rules_list', methods=method_post)
def iptables_get_ip_rules_list():
    """获取防火墙ip规则"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "get_ip_rules_list"):
        result = fw.get_ip_rules_list(pdata)
        return public.returnMsg(True, result)

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/create_ip_rules', methods=method_post)
def iptables_create_ip_rules():
    """添加防火墙ip规则"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "create_ip_rules"):
        result = fw.create_ip_rules(pdata)
        return result

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/modify_ip_rules', methods=method_post)
def iptables_modify_ip_rules():
    """修改防火墙ip规则"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "modify_ip_rules"):
        result = fw.modify_ip_rules(pdata)
        return result

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/remove_ip_rules', methods=method_post)
def iptables_remove_ip_rules():
    """删除防防火墙ip规则"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    fw = firewall_main()
    if hasattr(fw, "remove_ip_rules"):
        result = fw.remove_ip_rules(pdata)
        return result

    return public.returnMsg(False, "未知的动作")


@app.route('/iptables/remove_all_ip_rules', methods=method_post)
def iptables_remove_all_ip_rules():
    """删除所有防防火墙ip规则"""
    pdata = DefaultMunch.fromDict({})
    fw = firewall_main()
    if hasattr(fw, "remove_all_ip_rules"):
        result = fw.remove_all_ip_rules(pdata)
        return result

    return public.returnMsg(False, "未知的动作")


@app.route('/server/process_info', methods=method_post)
def get_process_info():
    """"获取单个进程信息"""
    s = Server()
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        pid = recv_data["pid"]
        result = s.one_process(pid)
        return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/server/server_info', methods=method_post)
def get_server_info():
    """"获取单个服务信息"""
    s = Server()
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        result = s.one_service(service_name)
        return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/service_status', methods=method_post)
def get_service_status():
    """"获取服务状态"""
    sctr = Service_Ctrl()
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        result = sctr.service_status(service_name)
        return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/service_start', methods=method_post)
def service_start():
    """"开启服务"""
    sctr = Service_Ctrl()
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        result = sctr.service_start(service_name)
        return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/service_stop', methods=method_post)
def service_stop():
    """"停止服务"""
    sctr = Service_Ctrl()
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        result = sctr.service_stop(service_name)
        return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/service_restart', methods=method_post)
def service_restart():
    """"重启服务"""
    sctr = Service_Ctrl()
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        result = sctr.service_restart(service_name)
        return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/service_reload', methods=method_post)
def service_reload():
    """"重载服务"""
    sctr = Service_Ctrl()
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        result = sctr.service_reload(service_name)
        return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/mysql/config', methods=method_post)
def mysql_get_conf():
    """获取mysql配置"""
    mq = Mysql()
    result = mq.getConf()
    return result


@app.route('/mysql/log/dir', methods=method_post)
def mysql_get_log_dir():
    """获取mysql配置"""
    mq = Mysql()
    result = mq.getLogDir()
    return result


@app.route('/mysql/log', methods=method_post)
def mysql_get_log():
    """获取mysql配置"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    mq = Mysql()
    result = mq.getLog(pdata)
    return result


@app.route('/apache/config', methods=method_post)
def apache_get_conf():
    """获取apache配置"""
    ap = Apache()
    result = ap.getConf()
    return result


@app.route('/apache/log/dir', methods=method_post)
def apache_get_log_dir():
    """获取apache日志目录"""
    ap = Apache()
    result = ap.getLogDir()
    return result


@app.route('/apache/log', methods=method_post)
def apache_get_log():
    """获取apache日志"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    ap = Apache()
    result = ap.getLog(pdata)
    return result


@app.route('/nginx/config', methods=method_post)
def nginx_get_conf():
    """获取nginx配置"""
    ng = Nginx()
    result = ng.getConf()
    return result


@app.route('/nginx/log/dir', methods=method_post)
def nginx_get_log_dir():
    """获取nginx日志目录"""
    ng = Nginx()
    result = ng.getLogDir()
    return result


@app.route('/nginx/log', methods=method_post)
def nginx_get_log():
    """获取nginx日志"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    ng = Nginx()
    result = ng.getLog(pdata)
    return result


@app.route('/docker/config', methods=method_post)
def docker_get_conf():
    """获取docker配置"""
    docker = Docker()
    result = docker.getConf()
    return result


@app.route('/docker/log/dir', methods=method_post)
def docker_get_log_dir():
    """获取redis日志目录"""
    docker = Docker()
    result = docker.getLogDir()
    return result


@app.route('/docker/log', methods=method_post)
def docker_get_log():
    """获取docker日志"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    docker = Docker()
    result = docker.getLog(pdata)
    return result


@app.route('/snmp/config', methods=method_post)
def snmp_get_conf():
    """获取snmp配置"""
    snmp = Snmp()
    result = snmp.getConf()
    return result


@app.route('/snmp/log/dir', methods=method_post)
def snmp_get_log_dir():
    """获取snmp日志目录"""
    snmp = Snmp()
    result = snmp.getLogDir()
    return result


@app.route('/snmp/log', methods=method_post)
def snmp_get_log():
    """获取snmp日志"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    snmp = Snmp()
    result = snmp.getLog(pdata)
    return result


@app.route('/redis/config', methods=method_post)
def redis_get_conf():
    """获取redis配置"""
    redis = Redis()
    result = redis.getConf()
    return result


@app.route('/redis/log/dir', methods=method_post)
def redis_get_log_dir():
    """获取redis日志目录"""
    redis = Redis()
    result = redis.getLogDir()
    return result


@app.route('/redis/log', methods=method_post)
def redis_get_log():
    """获取redis日志"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    redis = Redis()
    result = redis.getLog(pdata)
    return result


@app.route('/ipcc/config', methods=method_post)
def ipcc_get_conf():
    """获取redis配置"""
    ipcc = Ipcc()
    result = ipcc.getConf()
    return result


@app.route('/ipcc/log/dir', methods=method_post)
def ipcc_get_log_dir():
    """获取ipcc日志目录"""
    ipcc = Ipcc()
    result = ipcc.getLogDir()
    return result


@app.route('/ipcc/log', methods=method_post)
def ipcc_get_log():
    """获取ipcc日志"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    ipcc = Ipcc()
    result = ipcc.getLog(pdata)
    return result


##############整合到一个接口##################
@app.route('/service/config', methods=method_post)
def get_conf():
    """获取配置"""
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        if service_name == "apache":
            ap = Apache()
            result = ap.getConf()
            return result
        if service_name == "nginx":
            ng = Nginx()
            result = ng.getConf()
            return result
        if service_name == "mysql":
            mq = Mysql()
            result = mq.getConf()
            return result
        if service_name == "php":
            php = Php()
            result = php.getConf()
            return result
        if service_name == "php-fpm":
            phpfpm = Phpfpm()
            result = phpfpm.getConf()
            return result
        if service_name == "snmp":
            snmp = Snmp()
            result = snmp.getConf()
            return result
        if service_name == "redis":
            redis = Redis()
            result = redis.getConf()
            return result
        if service_name == "docker":
            docker = Docker()
            result = docker.getConf()
            return result
        if service_name == "ipcc":
            ipcc = Ipcc()
            result = ipcc.getConf()
            return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/service/log/dir', methods=method_post)
def get_log_dir():
    """获取日志目录"""
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        if service_name == "apache":
            ap = Apache()
            result = ap.getLogDir()
            return result
        if service_name == "nginx":
            ng = Nginx()
            result = ng.getLogDir()
            return result
        if service_name == "mysql":
            mq = Mysql()
            result = mq.getLogDir()
            return result
        if service_name == "php":
            php = Php()
            result = php.getLogDir()
            return result
        if service_name == "php-fpm":
            phpfpm = Phpfpm()
            result = phpfpm.getLogDir()
            return result
        if service_name == "snmp":
            snmp = Snmp()
            result = snmp.getLogDir()
            return result
        if service_name == "redis":
            redis = Redis()
            result = redis.getLogDir()
            return result
        if service_name == "docker":
            docker = Docker()
            result = docker.getLogDir()
            return result
        if service_name == "ipcc":
            ipcc = Ipcc()
            result = ipcc.getLogDir()
            return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/service/log', methods=method_post)
def get_log():
    """获取日志"""
    pdata = DefaultMunch.fromDict(request.form.to_dict())
    recv_data = request.form.to_dict()
    res = {}
    if recv_data:
        service_name = recv_data["service_name"]
        if service_name == "apache":
            ap = Apache()
            result = ap.getLog(pdata)
            return result
        if service_name == "nginx":
            ng = Nginx()
            result = ng.getLog(pdata)
            return result
        if service_name == "mysql":
            mq = Mysql()
            result = mq.getLog(pdata)
            return result
        if service_name == "php":
            php = Php()
            result = php.getLog(pdata)
            return result
        if service_name == "php-fpm":
            phpfpm = Phpfpm()
            result = phpfpm.getLog(pdata)
            return result
        if service_name == "snmp":
            snmp = Snmp()
            result = snmp.getLog(pdata)
            return result
        if service_name == "redis":
            redis = Redis()
            result = redis.getLog(pdata)
            return result
        if service_name == "docker":
            docker = Docker()
            result = docker.getLog(pdata)
            return result
        if service_name == "ipcc":
            ipcc = Ipcc()
            result = ipcc.getLog(pdata)
            return result
    else:
        return public.returnMsg(False, "未知的动作")


@app.route('/service/chunk/log', methods=method_all)
def service_get_chunk_log():
    """获取apache日志"""
    if request.method == "POST":
        pdata = DefaultMunch.fromDict(request.form.to_dict())
        recv_data = request.form.to_dict()
    else:
        pdata = DefaultMunch.fromDict(request.args.to_dict())
        recv_data = request.args.to_dict()
    if recv_data:
        service_name = recv_data["service_name"]
        if service_name == "apache":
            server = Apache()
        if service_name == "nginx":
            server = Nginx()
        if service_name == "mysql":
            server = Mysql()
        if service_name == "php":
            server = Php()
        if service_name == "php-fpm":
            server = Phpfpm()
        if service_name == "snmp":
            server = Snmp()
        if service_name == "redis":
            server = Redis()
        if service_name == "docker":
            server = Docker()
        if service_name == "ipcc":
            server = Ipcc()
        result = server.getChunkLog(pdata)
        if isinstance(result, dict):
            return result
        else:
            return Response(stream_with_context(result))
    else:
        return public.returnMsg(False, "未知的动作")

#################################################
if __name__ == '__main__':
    fw = firewall_main()
    host = str(public.getConfig('server', 'host'))
    port = int(public.getConfig('server', 'port'))
    results = fw.get_rules_list({'p': '1', 'query': str(port)})
    for result in results:
        if result['ports'] == str(port):
            fw.remove_rules(DefaultMunch.fromDict(result))
    source = literal_eval(public.getConfig('access', 'access_ip'))
    for value in source:
        data_dict = {"protocol": "tcp", "ports": str(port), "types": "accept", "brief": "iptables 接口管理端口",
                     "source": value}
        fw.create_rules(DefaultMunch.fromDict(data_dict))
    if public.getConfig('env', 'app_env') == 'production':
        server = pywsgi.WSGIServer((host, port), app)
        server.serve_forever()
    else:
        app.run(host, port, Debug=True)
