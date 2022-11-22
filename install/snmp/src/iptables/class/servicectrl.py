# -*- coding:utf8 -*-
import os
import socket
import datetime
import subprocess
import sys
import re
import json

if sys.version_info[0] == 3:
    from importlib import reload
if sys.version_info[0] == 2:
    reload(sys)
    sys.setdefaultencoding('utf8')



class Service_Ctrl:

    def __init__(self):
        pass

    def get_strtime(self, text):
        text = text.replace("年", "-").replace("月", "-").replace("日", " ").replace("/", "-").strip()
        text = re.sub("\s+", " ", text)
        t = ""
        regex_list = [
            # 2013年8月15日 22:46:21
            "(\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2})",
            # "2013年8月15日 22:46"
            "(\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2})",
            # "2014年5月11日"
            "(\d{4}-\d{1,2}-\d{1,2})",
            # "2014年5月"
            "(\d{4}-\d{1,2})",
        ]

        for regex in regex_list:
            t = re.search(regex, text)
            if t:
                t = t.group(1)
                return t
            else:
                return ""
                # print("没有获取到有效日期")
        return t


    def system_command(self, command):
        shell = subprocess.Popen(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=True)
        stdout, stderr = shell.communicate()
        try:
            return stdout.decode("utf8"), stderr.decode("utf8"), shell.returncode
        except Exception as error:
            return stdout.decode("gbk"), error, shell.returncode


    def ip_address(self):
        # 查询本机ip
        try:
            s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
            s.connect(('1.82.82.8', 280))  # 可随意填一个不能ping通的
            ip = s.getsockname()[0]
            s.close()
            return ip
        except Exception as e:
            return e


    def service_logging(self, content):
        # 记录日志
        log_path = '/var/log/service_assurance_check'
        if not os.path.isdir(log_path):
            os.makedirs(log_path)
        log_name = '{}-{}.logs'.format('service_assurance_check', datetime.datetime.now().strftime('%Y-%m-%d'))
        file_name = os.path.join(log_path, log_name)
        with open(file_name, 'a+') as f:
            now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            contents = '{}\t{}\t{}\n'.format(now, self.ip_address(), content)
            f.write(contents)


    def keep_five_log_file(self):
        # 只保留五个日志文件
        log_path = "/var/log/service_assurance_check"
        log_command = "ls -at {}|grep -v '^\.'|head -5".format(log_path)
        stdout, error, code = self.system_command(log_command)
        save_log_name_list = stdout.strip().split('\n')
        remove_log_list = os.listdir(log_path)
        remove_log_list = [i for i in remove_log_list if i not in save_log_name_list]
        for file in remove_log_list:
            remove_file_path = os.path.join(log_path, file)
            remove_file_command = 'rm -rf {}'.format(remove_file_path)
            self.system_command(remove_file_command)


    def port_checker(self):
        # 端口检测
        service_down = list()
        # for port in [8121, 8122, 8123]:
        for port in [8121, 8122, 8123]:
            command = "/usr/sbin/ss -anltp  |grep -E '\<.*:{}\>'".format(port)
            stdout, stderr, code = self.system_command(command)
            print(stdout)
            if not stdout:
                log = 'port\t{}\t{}'.format(port, 'down')
                self.service_logging(log)
                service_down.append(port)
        return len(service_down)


    def service_status(self, service_name):
        # 服务状态
        res = {}
        info = {}
        time = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        if service_name:
            if service_name in ['ipcc']:
                command = "echo `ps aux | grep %s | grep -v 'color' | awk '{print $2}'`" % service_name
                stdout, stderr, code = self.system_command(command)
                result = stdout.strip().encode("utf-8")
                list = result.split()
                if list:
                    res['state'] = "success"
                    res['des'] = "成功"
                    info['status'] = "running"
                    info['runtime'] = str(time)
                else:
                    res['state'] = "fail"
                    res['des'] = "失败"
                    info['status'] = ""
                    info['runtime'] = ""
                    res['res'] = info
                return json.dumps(res)
            else:
                command = "systemctl status %s|grep -E 'Active'" % service_name
                stdout, stderr, code = self.system_command(command)
                list = stdout.strip().split('since')
                # print(list3)
                res['state'] = "success"
                res['des'] = "成功"
                info['status'] = list[0].replace('Active: ', '')
                info['status'] = info['status'].replace(" ", "")
                info['runtime'] = str(self.get_strtime(stdout.strip()))
                res['res'] = info
                return json.dumps(res)
        else:
            res['state'] = "fail"
            res['des'] = "失败"
            info['status'] = ""
            info['runtime'] = ""
            res['res'] = info
            return json.dumps(res)


    def service_checker(self):
        # 检测服务挂掉的数量
        service_down = list()
        services_sequence = [
            'httpd', 'nginx', 'iptables', 'mysql', 'php', 'php-fpm', 'docker', 'redis', 'snmpd', 'sshd'
        ]
        for service_name in services_sequence:
            status = self.service_status(service_name)
            if status != 'active':
                log = '{}\t{}'.format(service_name, 'not active')
                self.service_logging(log)
                service_down.append(service_name)

        return len(service_down)


    def service_stop(self, service_name):
        # 停止服务
        stop_sequence = [
            'httpd', 'nginx', 'iptables', 'mysql', 'php', 'php-fpm', 'docker', 'redis', 'snmpd', 'sshd'
        ]
        # for service_name in stop_sequence:
        res = {}
        info = {}
        if service_name:
            if service_name in ['ipcc']:
                self.system_command('killall -9 {} && sleep 0.5'.format(service_name))
                command = "echo `ps aux | grep %s | grep -v 'color' | awk '{print $2}'`" % service_name
                stdout, stderr, code = self.system_command(command)
                result = stdout.strip().encode("utf-8")
                list = result.split()
                if list == False:
                    res['state'] = "success"
                    res['des'] = "成功"
                    info['status'] = "stopping"
                    info['runtime'] = ""
                else:
                    res['state'] = "fail"
                    res['des'] = "失败"
                    info['status'] = ""
                    info['runtime'] = ""
                    res['res'] = info
                return json.dumps(res)
            elif service_name == "redis":
                self.system_command('systemctl kill {} && sleep 0.5'.format(service_name))
                self.system_command('systemctl stop {} && sleep 0.5'.format(service_name))
                return self.service_status(service_name)
            else:
                self.system_command('systemctl stop {} && sleep 0.5'.format(service_name))
                return self.service_status(service_name)
        else:
            res['state'] = "fail"
            res['des'] = "失败"
            info['status'] = ""
            info['runtime'] = ""
            res['res'] = info
            return json.dumps(res)


    def service_start(self, service_name):
        # 启动服务
        start_sequence = [
            'httpd', 'nginx', 'iptables', 'mysql', 'php', 'php-fpm', 'docker', 'redis', 'snmpd', 'sshd'
        ]
        # for service_name in start_sequence:
        res = {}
        info = {}
        if service_name:
            if service_name == "ipcc":
                time = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                self.system_command('/usr/local/ccenter/listen.sh  && sleep 0.5')
                command = "echo `ps aux | grep %s | grep -v 'color' | awk '{print $2}'`" % service_name
                stdout, stderr, code = self.system_command(command)
                result = stdout.strip().encode("utf-8")
                list = result.split()
                if list:
                    res['state'] = "success"
                    res['des'] = "成功"
                    info['status'] = "running"
                    info['runtime'] = str(time)
                else:
                    res['state'] = "fail"
                    res['des'] = "失败"
                    info['status'] = ""
                    info['runtime'] = ""
                    res['res'] = info
                return json.dumps(res)
            else:
                status = json.loads(self.service_status(service_name))
                if status["state"] == "active(exited)":
                    self.system_command('systemctl restart {} && sleep 0.5'.format(service_name))
                else:
                    self.system_command('systemctl start {} && sleep 0.5'.format(service_name))
                return self.service_status(service_name)
        else:
            res['state'] = "fail"
            res['des'] = "失败"
            info['status'] = ""
            info['runtime'] = ""
            res['res'] = info
            return json.dumps(res)


    def service_restart(self, service_name):
        # 重启服务
        start_sequence = [
            'httpd', 'nginx', 'iptables', 'mysql', 'php', 'php-fpm', 'docker', 'redis', 'snmpd', 'sshd'
        ]
        # for service_name in start_sequence:
        res = {}
        info = {}
        if service_name:
            if service_name == "ipcc":
                self.service_stop(service_name)
                self.service_start(service_name)
                return self.service_status(service_name)
            elif service_name == "redis":
                self.service_stop(service_name)
                self.service_start(service_name)
                return self.service_status(service_name)
            else:
                self.system_command('systemctl restart {} && sleep 0.5'.format(service_name))
                return self.service_status(service_name)
        else:
            res['state'] = "fail"
            res['des'] = "失败"
            info['status'] = ""
            info['runtime'] = ""
            res['res'] = info
            return json.dumps(res)


    def service_reload(self, service_name):
        # 重载服务
        start_sequence = [
            'php-fpm', 'nginx', 'iptables'
        ]
        res = {}
        info = {}
        if service_name in start_sequence:
            if service_name:
                self.system_command('systemctl reload {} && sleep 0.5'.format(service_name))
                return self.service_status(service_name)
            else:
                res['state'] = "fail"
                res['des'] = "失败"
                info['status'] = ""
                info['runtime'] = ""
                res['res'] = info
        else:
            res['state'] = "fail"
            res['des'] = "失败,服务不支持reload"
            info['status'] = ""
            info['runtime'] = ""
            res['res'] = info
        return json.dumps(res)
