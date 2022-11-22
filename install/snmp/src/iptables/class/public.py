# coding: utf-8

import configparser
import json
import os
import re
import socket
import sys
import time


def getConfig(section, key):
    if not os.path.exists(os.path.split(os.path.realpath(sys.argv[0]))[0] + '//data//config.ini'):
        raise IndexError("配置文件不存在")
    config = configparser.RawConfigParser()
    path = os.path.split(os.path.realpath(sys.argv[0]))[0] + '//data//config.ini'
    config.read(path, encoding="utf-8")
    return config.get(section, key)


def M(table):
    """
        @name 访问面板数据库
        @author hwliang<hwl@bt.cn>
        @table 被访问的表名(必需)
        @return db.Sql object
        ps: 默认访问data/default.db
    """
    import db
    with db.Sql() as sql:
        # sql = db.Sql()
        return sql.table(table)


def WriteFile(filename, s_body, mode='w+'):
    """
    写入文件内容
    @filename 文件名
    @s_body 欲写入的内容
    return bool 若文件不存在则尝试自动创建
    """
    try:
        fp = open(filename, mode)
        fp.write(s_body)
        fp.close()
        return True
    except:
        try:
            fp = open(filename, mode, encoding="utf-8")
            fp.write(s_body)
            fp.close()
            return True
        except:
            return False


def writeFile(filename, s_body, mode='w+'):
    '''
        @name 写入到指定文件
        @author hwliang<2021-06-09>
        @param filename<string> 文件名
        @param s_boey<string/bytes> 被写入的内容，字节或字符串
        @param mode<string> 文件打开模式，默认w+
        @return bool
    '''
    return WriteFile(filename, s_body, mode)


def ExecShell(cmdstring, timeout=None, shell=True, cwd=None, env=None, user=None):
    '''
        @name 执行命令
        @author hwliang<2021-08-19>
        @param cmdstring 命令 [必传]
        @param timeout 超时时间
        @param shell 是否通过shell运行
        @param cwd 进入的目录
        @param env 环境变量
        @param user 执行用户名
        @return 命令执行结果
    '''
    a = ''
    e = ''
    import subprocess, tempfile
    preexec_fn = None
    tmp_dir = '/dev/shm'
    if user:
        preexec_fn = get_preexec_fn(user)
        tmp_dir = '/tmp'
    try:
        rx = md5(cmdstring)
        succ_f = tempfile.SpooledTemporaryFile(max_size=4096, mode='wb+', suffix='_succ', prefix='btex_' + rx,
                                               dir=tmp_dir)
        err_f = tempfile.SpooledTemporaryFile(max_size=4096, mode='wb+', suffix='_err', prefix='btex_' + rx,
                                              dir=tmp_dir)
        sub = subprocess.Popen(cmdstring, close_fds=True, shell=shell, bufsize=128, stdout=succ_f, stderr=err_f,
                               cwd=cwd, env=env, preexec_fn=preexec_fn)
        if timeout:
            s = 0
            d = 0.01
            while sub.poll() == None:
                time.sleep(d)
                s += d
                if s >= timeout:
                    if not err_f.closed: err_f.close()
                    if not succ_f.closed: succ_f.close()
                    return 'Timed out'
        else:
            sub.wait()

        err_f.seek(0)
        succ_f.seek(0)
        a = succ_f.read()
        e = err_f.read()
        if not err_f.closed: err_f.close()
        if not succ_f.closed: succ_f.close()
    except:
        return '', get_error_info()
    try:
        # 编码修正
        if type(a) == bytes: a = a.decode('utf-8')
        if type(e) == bytes: e = e.decode('utf-8')
    except:
        a = str(a)
        e = str(e)

    return a, e


def get_preexec_fn(run_user):
    '''
        @name 获取指定执行用户预处理函数
        @author hwliang<2021-08-19>
        @param run_user<string> 运行用户
        @return 预处理函数
    '''
    import pwd
    pid = pwd.getpwnam(run_user)
    uid = pid.pw_uid
    gid = pid.pw_gid

    def _exec_rn():
        os.setgid(gid)
        os.setuid(uid)

    return _exec_rn


def get_error_info():
    import traceback
    errorMsg = traceback.format_exc()
    return errorMsg


def Md5(strings):
    """
        @name 生成MD5
        @author hwliang<hwl@bt.cn>
        @param strings 要被处理的字符串
        @return string(32)
    """
    if type(strings) != bytes:
        strings = strings.encode()
    import hashlib
    m = hashlib.md5()
    m.update(strings)
    return m.hexdigest()


def md5(strings):
    return Md5(strings)


def ReturnMsg(status, msg, args=()):
    """
        @name 取通用dict返回
        @author hwliang<hwl@bt.cn>
        @param status  返回状态
        @param msg  返回消息
        @return dict  {"status":bool,"msg":string}
    """
    if type(msg) == str:
        for i in range(len(args)):
            rep = '{' + str(i + 1) + '}'
            msg = msg.replace(rep, args[i])
    return {'status': status, 'msg': msg}


def returnMsg(status, msg, args=()):
    """
        @name 取通用dict返回
        @author hwliang<hwl@bt.cn>
        @param status  返回状态
        @param msg  返回消息
        @return dict  {"status":bool,"msg":string}
    """
    return ReturnMsg(status, msg, args)


def is_ipv4(ip):
    try:
        socket.inet_pton(socket.AF_INET, ip)
    except AttributeError:
        try:
            socket.inet_aton(ip)
        except socket.error:
            return False
        return ip.count('.') == 3
    except socket.error:
        return False
    return True


def is_ipv6(ip):
    try:
        socket.inet_pton(socket.AF_INET6, ip)
    except socket.error:
        return False
    return True


def check_ip(ip):
    return is_ipv4(ip) or is_ipv6(ip)


def ReadFile(filename, mode='r'):
    """
    读取文件内容
    @filename 文件名
    return string(bin) 若文件不存在，则返回None
    """
    import os
    if not os.path.exists(filename): return False
    fp = None
    try:
        fp = open(filename, mode)
        f_body = fp.read()
    except Exception as ex:
        if sys.version_info[0] != 2:
            try:
                fp = open(filename, mode, encoding="utf-8")
                f_body = fp.read()
            except:
                try:
                    fp = open(filename, 'r', encoding="latin1")
                    f_body = fp.read()
                except:
                    fp = open(filename, 'r', encoding="GBK")
                    f_body = fp.read()
        else:
            return False
    finally:
        if fp and not fp.closed:
            fp.close()
    return f_body


def readFile(filename, mode='r'):
    '''
        @name 读取指定文件数据
        @author hwliang<2021-06-09>
        @param filename<string> 文件名
        @param mode<string> 文件打开模式，默认r
        @return string or bytes or False 如果返回False则说明读取失败
    '''
    return ReadFile(filename, mode)


def exists_args(args, get):
    '''
        @name 检查参数是否存在
        @author hwliang<2021-06-08>
        @param args<list or str> 参数列表 允许是列表或字符串
        @param get<dict_obj> 参数对像
        @return bool 都存在返回True，否则抛出KeyError异常
    '''
    if type(args) == str:
        args = args.split(',')
    for arg in args:
        if not arg in get:
            raise KeyError('缺少必要参数: {}'.format(arg))
    return True


# xss 防御
def xssencode(text):
    return text


# xss 防御
def xssencode2(text):
    return text


# 校验路径安全
def path_safe_check(path, force=True):
    if len(path) > 256: return False
    checks = ['..', './', '\\', '%', '$', '^', '&', '*', '~', '"', "'", ';', '|', '{', '}', '`']
    for c in checks:
        if path.find(c) != -1: return False
    if force:
        rep = r"^[\w\s\.\/-]+$"
        if not re.match(rep, path): return False
    return True


def formatSize(bytes):
    try:
        bytes = float(bytes)
        kb = bytes / 1024
    except:
        return "%fbytes" % (bytes)

    if kb >= 1024:
        M = kb / 1024
        if M >= 1024:
            G = M / 1024
            return "%.2fG" % (G)
        else:
            return "%.2fM" % (M)
    else:
        return "%.2fkb" % (kb)


def formatTime(atime):
    return time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(atime))


# 取通用对象
class dict_obj:
    def __contains__(self, key):
        return getattr(self, key, None)

    def __setitem__(self, key, value):
        setattr(self, key, value)

    def __getitem__(self, key):
        return getattr(self, key, None)

    def __delitem__(self, key):
        delattr(self, key)

    def __delattr__(self, key):
        delattr(self, key)

    def get_items(self):
        return self

    def exists(self, keys):
        return exists_args(keys, self)

    def get(self, key, default='', format='', limit=[]):
        '''
            @name 获取指定参数
            @param key<string> 参数名称，允许在/后面限制参数格式，请参考参数值格式(format)
            @param default<string> 默认值，默认空字符串
            @param format<string>  参数值格式(int|str|port|float|json|xss|path|url|ip|ipv4|ipv6|letter|mail|phone|正则表达式|>1|<1|=1)，默认为空
            @param limit<list> 限制参数值内容
            @param return mixed
        '''
        if key.find('/') != -1:
            key, format = key.split('/')
        result = getattr(self, key, default)
        if isinstance(result, str): result = result.strip()
        if format:
            if format in ['str', 'string', 's']:
                result = str(result)
            elif format in ['int', 'd']:
                try:
                    result = int(result)
                except:
                    raise ValueError("参数：{}，要求int类型数据".format(key))
            elif format in ['float', 'f']:
                try:
                    result = float(result)
                except:
                    raise ValueError("参数：{}，要求float类型数据".format(key))
            elif format in ['json', 'j']:
                try:
                    result = json.loads(result)
                except:
                    raise ValueError("参数：{}, 要求JSON字符串".format(key))
            elif format in ['xss', 'x']:
                result = xssencode(result)
            elif format in ['path', 'p']:
                if not path_safe_check(result):
                    raise ValueError("参数：{}，要求正确的路径格式".format(key))
                result = result.replace('//', '/')
            elif format in ['url', 'u']:
                regex = re.compile(
                    r'^(?:http|ftp)s?://'
                    r'(?:(?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+(?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)|'
                    r'localhost|'
                    r'\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})'
                    r'(?::\d+)?'
                    r'(?:/?|[/?]\S+)$', re.IGNORECASE)
                if not re.match(regex, result):
                    raise ValueError('参数：{}，要求正确的URL格式'.format(key))
            elif format in ['ip', 'ipaddr', 'i', 'ipv4', 'ipv6']:
                if format == 'ipv4':
                    if not is_ipv4(result):
                        raise ValueError('参数：{}，要求正确的ipv4地址'.format(key))
                elif format == 'ipv6':
                    if not is_ipv6(result):
                        raise ValueError('参数：{}，要求正确的ipv6地址'.format(key))
                else:
                    if not is_ipv4(result) and not is_ipv6(result):
                        raise ValueError('参数：{}，要求正确的ipv4/ipv6地址'.format(key))
            elif format in ['w', 'letter']:
                if not re.match(r'^\w+$', result):
                    raise ValueError('参数：{}，要求只能是英文字母或数据组成'.format(key))
            elif format in ['email', 'mail', 'm']:
                if not re.match(r"^.+\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)$", result):
                    raise ValueError("参数：{}，要求正确的邮箱地址格式".format(key))
            elif format in ['phone', 'mobile', 'p']:
                if not re.match("^[0-9]{11,11}$", result):
                    raise ValueError("参数：{}，要求手机号码格式".format(key))
            elif format in ['port']:
                result_port = int(result)
                if result_port > 65535 or result_port < 0:
                    raise ValueError("参数：{}，要求端口号为0-65535".format(key))
                result = result_port
            elif re.match(r"^[<>=]\d+$", result):
                operator = format[0]
                length = int(format[1:].strip())
                result_len = len(result)
                error_obj = ValueError("参数：{}，要求长度为{}".format(key, format))
                if operator == '=':
                    if result_len != length:
                        raise error_obj
                elif operator == '>':
                    if result_len < length:
                        raise error_obj
                else:
                    if result_len > length:
                        raise error_obj
            elif format[0] in ['^', '(', '[', '\\', '.'] or format[-1] in ['$', ')', ']', '+', '}']:
                if not re.match(format, result):
                    raise ValueError("指定参数格式不正确, {}:{}".format(key, format))

        if limit:
            if not result in limit:
                raise ValueError("指定参数值范围不正确, {}:{}".format(key, limit))
        return result


def ReturnJson(status, msg, args=()):
    """
        @name 取通用Json返回
        @author hwliang<hwl@bt.cn>
        @param status  返回状态
        @param msg  返回消息
        @return string(json)
    """
    return GetJson(ReturnMsg(status, msg, args))


def GetJson(data):
    """
    将对象转换为JSON
    @data 被转换的对象(dict/list/str/int...)
    """
    from json import dumps
    if data == bytes:
        data = data.decode('utf-8')
    try:
        return dumps(data, ensure_ascii=False)
    except:
        return dumps(returnMsg(False, "错误的响应: %s" % str(data)))
