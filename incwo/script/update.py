#!/usr/bin/env python3

import csv
import os
import requests
import config

#retrieve info from local config.py
url = config.url
login = config.login
pwd = config.pwd
from_page = config.from_page
to_page = config.to_page

#log file
f = open('incwo-log.txt','w')

def myprint(s):
    f.write(s + '\n')

def delAll():
    filename = 'id.txt'
    if not os.path.exists(filename):
        print("None deleted, cannot read file " + filename)
        return

    f = open(filename, 'r')
    for line in f:
        delurl = url + line.strip() + ".xml"
        print("DELETE " + delurl)
        #res = requests.delete(delurl, auth=(login, pwd))
        # print(res)
        #delId(line.strip())


def delId(id):
    delurl = url + id + ".xml"
    print("DELETE " + delurl)
    # res = requests.delete(delurl, auth=(login, pwd))
    # print(res)
    return res    


def updateName(id, name):
    update(id, "<customer_product><name>" + name + "</name></customer_product>")


def updateActive(id, isactive):
    update(id, "<customer_product><is_active>" + isactive + "</is_active></customer_product>")


def updatePrice(id, arg, tva):
    ht = float(arg) / (1 + float(tva)/100)
    update(id, "<customer_product><price_in_file_currency>" + ht + "</price_in_file_currency><price>" 
    + ht + "</price><entered_price_in_ttc>"
     + arg + "</entered_price_in_ttc><entered_price_in_ttc_in_file_currency>"
      + arg + "</entered_price_in_ttc_in_file_currency></customer_product>")


def updateStock(id, arg):
    update(id, "<customer_product><total_stock>" + arg + "</total_stock></customer_product>")
    

def update(id, dataval):
    upurl = url + id + ".xml"
    res = requests.put(upurl,
      auth=(login, pwd),
      #headers={'Content-Type': 'application/xml; charset=utf-8'},
      headers={'Content-Type': 'application/xml'},
      #data=dataval)
      data=dataval.encode('utf-8'))
      
    print(res)


def getAll(page, dictref2id, dictid2ref):
    req = url[:-1] + ".xml?page=" + page
    print ("HTTP request " + req)
    res = requests.get(req, auth=(login, pwd))
    print(res)
    
    id = None
    ref = None
    isActive = None
    #print(res.text)
    for line in res.text.splitlines():
        if id and ref and isActive:
            if isActive == "1":
                if ref in dictref2id:
                    myprint("doublon : " + url + dictref2id[ref] + " -> " + url + id)
                dictref2id[ref] = id
                dictid2ref[id] = ref
            id = None
            ref = None
            isActive = None
        if "<id>" in line:
            id = line[line.find("<id>")+4:line.find("<\id>")-4]
        if "<reference>" in line:
            ref = line[line.find("<reference>")+11:line.find("<\reference>")-11]
        if "<is_active>" in line:
            isActive = line[line.find("<is_active>")+11:line.find("<\is_active>")-11]

    return dict


#Reference,"Nom du produit","Code-barre EAN",Classification,"Categorie de produit","Prix de vente TTC","Taux de tva","Stock entrepot 1"
def readCsv(filename, dict):
    if not os.path.exists(filename):
        print("Csv file not exist and so ignored " + filename)
        return

    with open(filename, 'r') as csvfile:
        spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
        for row in spamreader:
            dict[row[0]]=row


def doAll():
    incwoArticles = {}
    incwoArticles2 = {}
    for i in range (from_page, to_page):
        getAll(str(i), incwoArticles, incwoArticles2)
    print(len(incwoArticles))
    print(len(incwoArticles2))

    factuxArticles = {}
    readCsv("factux.csv", factuxArticles)
    print(len(factuxArticles))

    nb = 0
    for key in incwoArticles:
        if key not in factuxArticles.keys():
            #print(key + " to disable " + incwoArticles[key])
            nb += 1
            #updateActive(incwoArticles[key], "0")
    # print(str(nb) + " articles to delete !!!!!!!!!!!!!!!")

    nb = 0
    for key in factuxArticles:
        if key in incwoArticles.keys():
            incwoId = incwoArticles[key]
            #print("update " + key + " / " + incwoId + ", " + factuxArticles[key][1])
            #updateName(incwoId, factuxArticles[key][1])
            #updateStock(incwoId, factuxArticles[key][7])
        else:
            nb = nb
            # print(key + " a creer")
        nb += 1
    # print(str(nb) + " articles to create/update !!!!!!!!!!!!!!!")


def main():
    doAll()


if __name__ == '__main__':
    main()


# get all id from catalog
# curl -u login:pwd -X GET -H 'Content-Type: application/xml'  https://www.incwo.com/555938/customer_products.xml > prod.txt
# cat prod.txt | grep "<id>" | cut -c9-16 > id.txt


# update with xml
# curl -u login:pwd -X PUT -H 'Content-Type: application/xml' -d '<customer_product><enter_prices_in_ttc>8</enter_prices_in_ttc></customer_product>' https://www.incwo.com/555938/customer_products/15567882.xml
