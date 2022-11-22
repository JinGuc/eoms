import public
from File import File
from ast import literal_eval

class   Phpfpm:
    __config = ""
    __file = ""

    def __init__(self):
        self.__file = File()
        pass

    def getConf(self):
        file = "php-fpm.conf"
        path = public.getConfig('php-fpm', 'conf')
        result = self.__file.getFileInfo(path, file)
        if not result:
            return public.returnMsg(False, '文件不存在或者为空')
        return public.returnMsg(True, str(result))

    def getLog(self, args):
        if 'fileName' not in args:
            return public.returnMsg(False, '参数缺失')
        fileName = args.fileName
        path = public.getConfig('php-fpm', 'log')
        result = self.__file.getFileInfo(path, fileName)
        if not result:
            return public.returnMsg(False, '文件不存在或者为空')
        return public.returnMsg(True, str(result))

    def getLogDir(self):
        path = public.getConfig('php-fpm', 'log')
        result = self.__file.getDirInfo(path, literal_eval(public.getConfig('php-fpm', 'logFilter')))
        return public.ReturnJson(True, result)
