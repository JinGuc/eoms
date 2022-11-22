import os
import public
from File import File
from ast import literal_eval

class Apache:
    __apacheType = 0
    __file = ""

    def __init__(self):
        self.__file = File()
        if os.path.exists('/etc/init.d/httpd'):
            result = public.ExecShell('systemctl status httpd')[
                0].replace("\n", "")
            if result:
                pass
                self.__apacheType = 1
            else:
                self.__apacheType = 2

    def getStatus(self):
        pass

    def getConf(self):
        file = "httpd.cnf"
        path = public.getConfig('apache', 'conf')
        result = self.__file.getFileInfo(path, file)
        if not result:
            return public.returnMsg(False, '文件不存在或者为空')
        return public.returnMsg(True, str(result))

    def getLog(self, args):
        if 'fileName' not in args:
            return public.returnMsg(False, '参数缺失')
        fileName = args.fileName
        path = public.getConfig('apache', 'log')
        result = self.__file.getFileInfo(path, fileName)
        if not result:
            return public.returnMsg(False, '文件不存在或者为空')
        return public.returnMsg(True, str(result))

    def getLogDir(self):
        path = public.getConfig('apache', 'log')
        result = self.__file.getDirInfo(path, literal_eval(public.getConfig('apache', 'logFilter')))
        return public.ReturnJson(True, result)

    def getChunkLog(self, args):
        if 'fileName' not in args:
            return public.returnMsg(False, '参数缺失')
        fileName = args.fileName
        path = public.getConfig('apache', 'log')
        result = self.__file.getFileInfoChunk(path, fileName, args.start if 'start' in args else "1")
        if result is None:
            return public.returnMsg(False, '文件不存在或者为空')
        return public.returnMsg(True, result)
