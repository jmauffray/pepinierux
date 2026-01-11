#!/usr/bin/env python3

import csv
import os
import requests
import time
import config

# retrieve info from local config.py
url = config.url
login = config.login
pwd = config.pwd


def updateActive(id, isactive):
    update(id, "<customer_product><is_active>" + isactive
           + "</is_active></customer_product>")


def update(id, dataval):
    upurl = url + id + ".xml"
    print("url:" + upurl)
    print("data:" + dataval)

    res = requests.put(
        upurl,
        auth=(login, pwd),
        # headers={'Content-Type': 'application/xml; charset=utf-8'},
        headers={'Content-Type': 'application/xml'},
        # data=dataval)
        data=dataval.encode('utf-8'))

    print(res)
    print(res.content)


def readCsv(filename, dict):
    if not os.path.exists(filename):
        print("CSV file not exist and so ignored " + filename)
        return

    with open(filename, 'r') as csvfile:
        spamreader = csv.reader(csvfile, delimiter=';', quotechar='"')
        for row in spamreader:
            dict[row[0]] = row


def doAll():
    incwoArticles = {}
    readCsv("file2.csv", incwoArticles)
    print("length:" + str(len(incwoArticles)))

    for key in incwoArticles:
        updateActive(key, "0")
        print(key + " to update")
        time.sleep(1/10)


def main():
    doAll()


if __name__ == '__main__':
    main()

# get all id from catalog
# curl -u login:pwd -X GET -H 'Content-Type: application/xml'  https://www.incwo.com/xxxxxxx/customer_products.xml > prod.txt
# cat prod.txt | grep "<id>" | cut -c9-16 > id.txt

# update with xml
# curl -u login:pwd -X PUT -H 'Content-Type: application/xml' -d '<customer_product><enter_prices_in_ttc>8</enter_prices_in_ttc></customer_product>' https://www.incwo.com/xxxxxxx/customer_products/yyyyyyyyyyy.xml

# curl -u login:pwd -X PUT -d '<customer_product><is_active>1</is_active></customer_product>' -H 'Content-Type: application/xml'  https://www.incwo.com/customer_products/show/xxxxxxxx/yyyyyyyyyyy.xml
