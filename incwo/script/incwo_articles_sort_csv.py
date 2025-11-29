#!/usr/bin/env python3

import csv

dico = {}

# phase de lecture
with open('data.csv', newline='') as csvfile:
    spamreader = csv.reader(csvfile, delimiter=',', quotechar='|')
    next(spamreader)  # saute la première ligne
    for row in spamreader:
        v1 = int(row[0])  # correspond à l'id incwo
        v2 = int(row[1])  # correspond à la référence produit
        if v2 in dico:
            dico[v2].append((v1, v2))
        else:
            dico[v2] = [(v1, v2)]
            # on associe une liste à chaque référence produit

# phase d'écriture
with open('file1.csv', 'w', newline='') as f1, \
     open('file2.csv', 'w', newline='') as f2:

    w1 = csv.writer(f1, delimiter=';')
    w2 = csv.writer(f2, delimiter=';')

    w1.writerow("incwo_id, Reference produit")
    w2.writerow("incwo_id, Reference produit")

    for v in dico:  # pour chaque ref produit
        lcouples = dico[v]  # liste des couples id incwo,ref produit
        lids = list(map(lambda x: x[0], dico[v]))  # liste des id incwo
        m = max(lids)
        for (idinc, ref) in lcouples:
            if idinc == m:
                w1.writerow([idinc, ref])
            else:
                w2.writerow([idinc, ref])
