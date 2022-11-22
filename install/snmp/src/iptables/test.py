import psutil
import time
import json

KEY_LIST = [
    "status", # 进程状态
    "pid", # 进程号
    "create_time", # 进程启动时间
    "cpu_percent", # cpu占用率
    "username", # 启动进程的用户
    "num_threads", # 线程数量
    "memory_percent", # 内存使用率
    "cmdline", # 启动命令
    "cpu_affinity" # 使用了哪些核
]

def common_monitor(key_lis):

    """记录当前所有进程的状态"""

    pids = psutil.pids()

    pids_lis = []

    for pid in pids:

        if psutil.pid_exists(pid):

            pid_obj = psutil.Process(pid)

            dic = pid_obj.as_dict(attrs=key_lis)

            dic['cpu_affinity'] = str(dic['cpu_affinity'])

            dic['cmdline'] = ' '.join(dic['cmdline'])

            dic['create_time'] = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(dic['create_time']))

            pids_lis.append(dic)

    return pids_lis

def one_process(pid, key_lis):

    """ 记录某个进程的状态 """

    pid_obj = psutil.Process(pid)

    dic = pid_obj.as_dict(attrs=key_lis)
    print(dic)

    dic['cpu_affinity'] = str(dic['cpu_affinity'])

    dic['cmdline'] = ' '.join(dic['cmdline'])

    dic['create_time'] = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(dic['create_time']))

    return dic

if __name__ == "__main__":
    ## 查看所有进程
    # res = common_monitor(KEY_LIST)
    # print(json.dumps(res, indent=4))

    # 请确保进程号存在

    res = one_process(5487, KEY_LIST)

    print(json.dumps(res, indent=4))