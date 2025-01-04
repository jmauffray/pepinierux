import os
import time
from datetime import datetime

HOSTBASE = "192.168.1."
ROUTE = "/index.htm"
STRREF = "IP-TO-UPDATE"
FILEREF = "/etc/motion/camera-pepiniere.conf.in"
FILEOUT = "/etc/motion/camera-pepiniere.conf"


def check():
    for i in range(100, 150):
        ip = HOSTBASE + str(i)
        url = "http://" + ip + ROUTE
        res = os.system("wget -O login.asp " + url)
        if res == 0:
            print("OK:" + ip)
            res = os.system("sed 's/" + STRREF + "/" + ip + "/' " + FILEREF + " > " + FILEOUT)
            os.system("motion >> motion.log")
            return 0
        else:
            print("KO:" + ip)
    print("start finished with error")
    return 1


res = 1
while res != 0:
    print(datetime.now())
    res = check()
    time.sleep(1800)
