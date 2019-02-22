# curl -u login:pwd -X GET -H 'Content-Type: application/xml'  https://www.incwo.com/.../customer_products.xml > prod.txt
# cat prod.txt | grep "<id>" | cut -c9-16 > id.txt

import csv
import requests

import config
#config.py content
#url = "https://www.incwo.com/.../customer_products"
#login = "..."
#pwd = "..."

def delAll():
    f = open('id.txt', 'r')
    for line in f:
        delurl = config.url + "/" + line.strip() + ".xml"
        print("DELETE " + delurl)
        res = requests.delete(delurl, auth=(login, pwd))
        print(res)

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
    upurl = config.url + "/" + id + ".xml"
    res = requests.put(upurl,
      auth=(login, pwd),
      #headers={'Content-Type': 'application/xml; charset=utf-8'},
      headers={'Content-Type': 'application/xml'},
      #data=dataval)
      data=dataval.encode('utf-8'))
      
    print(res)

def getAll(page, dictref2id, dictid2ref):
    res = requests.get(config.url + ".xml?page=" + page, auth=(config.login, config.pwd))
    print(res)
    
    id = None
    ref = None
    for line in res.text.splitlines():
        if id and ref:
            if ref in dictref2id:
                print(ref + " en double")
            dictref2id[ref] = id
            dictid2ref[id] = ref
            id = None
            ref = None
        if "<id>" in line:
            id = line[line.find("<id>")+4:line.find("<\id>")-4]
        if "<reference>" in line:
            ref = line[line.find("<reference>")+11:line.find("<\reference>")-11]

    return dict

#Référence,"Nom du produit","Code-barre EAN",Classification,"Catégorie de produit","Prix de vente TTC","Taux de tva","Stock entrepot 1"
def readCsv(filename, dict):
    with open(filename, 'r') as csvfile:
        spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
        for row in spamreader:
            dict[row[0]]=row


def doall():
    incwoArticles = {}
    incwoArticles2 = {}
    for i in range (1, 11):
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
    print(str(nb) + " articles to delete !!!!!!!!!!!!!!!")


    nb = 0
    for key in factuxArticles:
        if key in incwoArticles.keys():
            incwoId = incwoArticles[key]
            #print("update " + key + " / " + incwoId + ", " + factuxArticles[key][1])
            #updateName(incwoId, factuxArticles[key][1])
            #updateStock(incwoId, factuxArticles[key][7])
        else:
            print(key + " à créer")
        nb += 1
    print(str(nb) + " articles to create/update !!!!!!!!!!!!!!!")



#updateActive("12804398", "0")
#updateName("14346703", "Ligustrum , Ovalifolium Aureum, 80/100, reculture, tun6")
#updateName("14346703", "Ligustrum")

#updateStock("12825568", "24.00")
doall()


