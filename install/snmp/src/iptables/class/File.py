# coding: utf-8
import os
import sys
import math
import public
import chardet
import pwd
import grp
from functools import partial


class File:
    __dir = ""

    def __init__(self):
        pass

    def getDirInfo(self, dir, filter):
        data = []
        for root, dirs, files in os.walk(dir):
            for file in files:
                fileInfo = {}
                if filter:
                    if any(f in file for f in filter):
                        fileInfoTmp = os.stat(f"{dir}{file}")
                        fileInfo["fileName"] = file
                        fileInfo["fileSize"] = public.formatSize(fileInfoTmp.st_size)
                        fileInfo["lastModificationTime"] = public.formatTime(fileInfoTmp.st_mtime)
                        fileInfo["lastAccessTime"] = public.formatTime(fileInfoTmp.st_atime)
                        fileInfo["LastStateChangTime"] = public.formatTime(fileInfoTmp.st_ctime)
                        fileInfo["owner"] = pwd.getpwuid(fileInfoTmp.st_uid)[0]
                        fileInfo["group"] = grp.getgrgid(fileInfoTmp.st_gid)[0]
                        fileInfo["mode"] = oct(fileInfoTmp.st_mode)[-3:]
                        data.append(fileInfo)
                else:
                    fileInfoTmp = os.stat(f"{dir}{file}")
                    fileInfo["fileName"] = file
                    fileInfo["fileSize"] = public.formatSize(fileInfoTmp.st_size)
                    fileInfo["lastModificationTime"] = public.formatTime(fileInfoTmp.st_mtime)
                    fileInfo["lastAccessTime"] = public.formatTime(fileInfoTmp.st_atime)
                    fileInfo["LastStateChangTime"] = public.formatTime(fileInfoTmp.st_ctime)
                    fileInfo["owner"] = pwd.getpwuid(fileInfoTmp.st_uid)[0]
                    fileInfo["group"] = grp.getgrgid(fileInfoTmp.st_gid)[0]
                    fileInfo["mode"] = oct(fileInfoTmp.st_mode)[-3:]
                    data.append(fileInfo)
        return data

    def getFileInfo(self, path, file):
        filename = f"{path}{file}"
        fp = None
        try:
            fp = open(filename, 'r')
            f_body = fp.read()
        except Exception as ex:
            if sys.version_info[0] != 2:
                try:
                    fp = open(filename, 'r', encoding="utf-8")
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

    def getFileInfoChunk(self, path, file, start=1):
        data = {}
        pagesize = 1000
        filename = f"{path}{file}"
        fileInfo = os.stat(filename)
        data["fileSize"] = public.formatSize(fileInfo.st_size)
        data["lastModificationTime"] = public.formatSize(fileInfo.st_mtime)
        data["lastAccessTime"] = public.formatTime(fileInfo.st_atime)
        data["LastStateChangTime"] = public.formatTime(fileInfo.st_ctime)
        data["pagesize"] = pagesize
        data["startLine"] = int(start)
        totalLine, endLine, fileContent = self.getFileData(filename, data)
        data["totalLine"] = totalLine
        data["fileContent"] = fileContent
        data["endLine"] = int(endLine)
        return data

    def getFile(self, filename, block_size=1024 * 8):
        try:
            with open(filename, "r") as fp:
                for chunk in iter(partial(fp.read, block_size), ""):
                    yield chunk
        except Exception as ex:
            if sys.version_info[0] != 2:
                try:
                    with open(filename, "r", encoding="utf8") as fp:
                        for chunk in iter(partial(fp.read, block_size), ""):
                            yield chunk
                except:
                    try:
                        with open(filename, "r", encoding="latin1") as fp:
                            for chunk in iter(partial(fp.read, block_size), ""):
                                yield chunk
                    except:
                        with open(filename, "r", encoding="gbk") as fp:
                            for chunk in iter(partial(fp.read, block_size), ""):
                                yield chunk
        return None

    def getFileData(self, filename, data):
        count = -1
        result = ""
        pagesize = data["pagesize"]
        endLine = data["startLine"]
        try:
            for count, line in enumerate(open(filename, 'rU')):
                count += 1
                if count > data["startLine"] and pagesize > 0:
                    pagesize -= 1
                    endLine += 1
                    result = result + line
        except Exception as ex:
            if sys.version_info[0] != 2:
                try:
                    for count, line in enumerate(open(filename, 'rU', encoding="utf8")):
                        count += 1
                        if count > data["startLine"] and pagesize > 0:
                            pagesize -= 1
                            endLine += 1
                            result = result + line
                except:
                    try:
                        for count, line in enumerate(open(filename, 'rU', encoding="latin1")):
                            count += 1
                            if count > data["startLine"] and pagesize > 0:
                                pagesize -= 1
                                endLine += 1
                                result = result + line
                    except:
                        for count, line in enumerate(open(filename, 'rU', encoding="gbk")):
                            count += 1
                            if count > data["startLine"] and pagesize > 0:
                                pagesize -= 1
                                endLine += 1
                                result = result + line
        return count, endLine, result
