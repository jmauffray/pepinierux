#!/usr/bin/env python

import csv
import time
from time import gmtime, strftime

ca = 0
l = []

#Retrieve incwo csv file from lignes de factues, utf8 converson
#UTF8 support : iconv -f ISO_8859-1 -t UTF-8 t.csv -o c.csv
with open('c.csv', newline='') as csvfile:
    spamreader = csv.reader(csvfile, delimiter=';')
    next(csvfile)
    for row in spamreader:
        t = time.strptime(row[74], "%a %b %d %H:%M:%S %z %Y")
        #ignore avoir
        if row[3] is "1":
            val = float(row[9].replace(',','.'))
            l.append((t, val))
            ca += val

print("Total CA:", ca)
print("Nombre de ticket:", len(l))

month = {}
day = {}
hour = {}
amday = {}
pmday = {}

for a in l:
    if a[0].tm_mon not in month:
        month[a[0].tm_mon] = a[1]
    month[a[0].tm_mon] += a[1]

    if a[0].tm_wday not in day:
        day[a[0].tm_wday] = a[1]
    day[a[0].tm_wday] += a[1]

    if a[0].tm_hour not in hour:
        hour[a[0].tm_hour] = a[1]
    hour[a[0].tm_hour] += a[1]

    if a[0].tm_wday not in amday or a[0].tm_wday not in pmday:
        if a[0].tm_hour < 13:
            amday[a[0].tm_wday] = a[1]
        else:
            pmday[a[0].tm_wday] = a[1]
    if a[0].tm_hour < 13:
        amday[a[0].tm_wday] += a[1]
    else:
        pmday[a[0].tm_wday] += a[1]

print("CA par mois:", month)
print("CA par jour:", day)
print("CA par heure:", hour)
print("CA par matin:", amday)
print("CA par apres midi:", pmday)
