import public
from File import File


class   Snmp:
    __config = ""
    __file = ""

    def __init__(self):
        self.__file = File()
        pass

    def getConf(self):
        file = "snmpd.conf"
        path = public.getConfig('snmp', 'conf')
        result = self.__file.getFileInfo(path, file)
        if not result:
            return public.returnMsg(False, '文件不存在或者为空')
        return public.returnMsg(True, str(result))

    def getLog(self, args):
        if 'fileName' not in args:
            return public.returnMsg(False, '参数缺失')
        fileName = args.fileName
        path = public.getConfig('snmp', 'log')
        result = self.__file.getFileInfo(path, fileName)
        if not result:
            return public.returnMsg(False, '文件不存在或者为空')
        return public.returnMsg(True, str(result))

    def getChunkLog(self, args):
        if 'fileName' not in args:
            return public.returnMsg(False, '参数缺失')
        fileName = args.fileName
        path = public.getConfig('snmp', 'log')
        result = self.__file.getFileInfoChunk(path, fileName, args.start if 'start' in args else "1")
        if result is None:
            return public.returnMsg(False, '文件不存在或者为空')
        return public.returnMsg(True, result)

    def getLogDir(self):
        path = public.getConfig('snmp', 'log')
        result = self.__file.getDirInfo(path, ['.log', '.err'])
        return public.ReturnJson(True, result)

    def setConf(self):
        file = "snmpd.conf"
        path = public.getConfig('snmp', 'conf')
        filepath = path+file
        contents = '\n#1122334444\n'
        with open(filepath, 'w', encoding='gb18030') as wf:
            wf.write(contents)
        return public.returnMsg(True, 'OK')    

