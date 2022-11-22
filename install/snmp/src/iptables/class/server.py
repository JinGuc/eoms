import psutil
import time
import subprocess
import json
import sys
if sys.version_info[0] == 3:
    from importlib import reload
if sys.version_info[0] == 2:
    reload(sys)
    sys.setdefaultencoding('utf8')


class Server:

    def __init__(self):
        pass
    def system_command(command):
            shell = subprocess.Popen(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=True)
            stdout, stderr = shell.communicate()
            try:
                return stdout.decode("utf8"), stderr.decode("utf8"), shell.returncode
            except Exception as error:
                return stdout.decode("gbk"), error, shell.returncode
    def Disk_io(self, pid):
        info = {}
        try:
            p = psutil.Process(pid)
            Read_count_1 = p.io_counters().read_count  # 读操作数
            Write_count_1 = p.io_counters().write_count  # 写操作数
            Read_bytes_1 = p.io_counters().read_bytes  # 读字节数
            Write_bytes_1 = p.io_counters().write_bytes  # 写字节数
            info['Read_count'] = str(Read_count_1)
            info['Write_count'] = str(Write_count_1)
            info['Read_bytes'] = Read_bytes_1
            info['Write_bytes'] = Write_bytes_1
            return info
        except:
            pass

    def info(self):
        m = 0
        list = []
        for proc in psutil.process_iter():
            try:
                pinfo = proc.as_dict(
                    attrs=['pid', 'name', 'ppid', 'username', 'status', 'num_threads', 'cpu_percent', 'memory_percent'])
            except psutil.NoSuchProcess:
                pass
            else:
                pid = pinfo['pid']
                process = psutil.Process(pid)
                num_fds = process.num_fds()
                is_running = process.is_running()
                create_time = process.create_time()
                pinfo['num_fds'] = num_fds
                pinfo['is_running'] = is_running
                pinfo['create_time'] = create_time
                Diskio = self.Disk_io(pid)
                pinfo['diskio'] = Diskio
                list.append(pinfo)
                m = m + 1
        return list

def one_process(self,pid):
    """ 记录某个进程的状态 """
    dic={}
    try:
        pid_obj = psutil.Process(pid)
        dic = pid_obj.as_dict(attrs=['pid', 'name', 'ppid', 'username', 'status', 'num_threads', 'cpu_percent', 'memory_percent'])
    except psutil.NoSuchProcess:
        pass
    else:
        dic['cpu_affinity'] = str(dic['cpu_affinity'])
        dic['cmdline'] = ' '.join(dic['cmdline'])
        dic['create_time'] = time.strftime(
        "%Y-%m-%d %H:%M:%S", time.localtime(dic['create_time']))    
        diskio = {}
        diskio['read_count'] = pid_obj.io_counters().read_count  # 读操作数
        diskio['write_count'] = pid_obj.io_counters().write_count  # 写操作数
        diskio['read_bytes'] = pid_obj.io_counters().read_bytes  # 读字节数
        diskio['write_bytes'] = pid_obj.io_counters().write_bytes  # 写字节数
        dic['diskio'] = diskio
    return json.dumps(dic)

def one_service(self,service_name):
    command = "echo `ps aux | grep %s | grep -v 'color' | awk '{print $2}'`" % service_name
    stdout, stderr, code = self.system_command(command)
    result=stdout.strip().encode("utf-8")
    list = result.split()
    cpu_percent_total=0
    memory_percent_total=0
    re={}
    re_=[]
    re['service_name']=service_name
    for i in list:
        res=one_process(int(i))

        if res:
            re_.append(res)
            cpu_percent_total=cpu_percent_total+res['cpu_percent']
            memory_percent_total=memory_percent_total+res['memory_percent']
        else:
            continue
    re['cpu_percent_total']=cpu_percent_total
    re['memory_percent_total']=memory_percent_total
    re['list']=re_
    return json.dumps(re)
        
