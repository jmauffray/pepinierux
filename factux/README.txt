#######################################################################


Installation de factux sur votre serveur

Version 1.1.5"

Avant l'installation assurer vous de recuperer 
1.l'adresse de votre base de donn�e
2.le mot de passe de l'utilisateur de votre base mysql
3.le login de votre utilisateur mysql
4.Si la base de donn�e � �t� cr�e le nom de cette base de donn�e vide
(si elle n'existe pas et que vous avez les droit necessaire sur le sertveur de base de donn�e,Factux la cr�eras pour vous
5.Toutes les info de votre entreprise (n� de tva, adresse, tel, registre de commerce...)


Decompressez le fichier dans un repertoire accecible par apache
Verifier les droit des fichier (chmod)
- Touts les fichier doivent etres lisibles par apache
- Les repertoires 
									/include 
									/dump 
									/image 	
									/include/session
									/fpdf
									et le repertoire racine de factux doivent permettre un droit d'ecriture par apache

Pointez votre navigateur sur http://votre_hebergeur.org/factux/installeur/
Suivez les instructions � l'ecran.
Apres l'installation il faut effacer le repertoire installeur de l'arboressence de factux (si vous ne le faites pas un message de rapel vous en feras le rappel)
R�duire les droit des fichier :
									/include/config/var.php
									/include/config/common.php
		Ceux ci ne doivent etre accesible quen lecture par apache.
Si Vous ne le faites pas un rappel vous seras adress� dans Factux

 Bugs et desiteratas sur http://factux.sourceforge.net 

######################################################

Upgrade de Factux

Faites tout d'abord une sauvgarde de votre base de donn�e (en ligne de commande ou via phpmyadmin ou tout autre soft de gestion de mysql)
Ensuite faite iun backup des fichiers suivant:
												 		 									/include/config/common.php
																							/include/config/var.php
														
Ceci fait decompresser l'archive et uploader les fichier dans le repertoire de factux en ecrassant les anciens fichiers.
Verifier les droits comme pour l'installation (voir plus haut) 
reuploader les fichiers:
			 		 									/include/config/common.php
														/include/config/var.php	
Si vous utliser l'euro comme devise:editer /include/config/var.php et remplacer $devise ="&#128;"; par $devise ="&euro;";
Pointez votre navigateur sur http://votre_hebergeur.org/factux/installeur/upgrade et suivez les instructions a l'ecran
# Si vous avez modifi� les fichier fact_pdf.php ou bon_pdf.php vous devrez malheureusement refaire ces modifications

!!!!!!!!!!!!!Je ne suis en aucun cas responsable des pertes de donn�es du � l'upgrade de Factux !!!!!!!!!!!!!
#####################################################
Changelog 1.1.4 --> 1.1.5

Chasse aux bugs
un module de themes et 4 themes
le choix des categories
l'integration du module lot
le choix avanc� des clients selon leur initiale
un systheme de choix de payement ds factures. 
Ajout d'un module de stock
La validation du code selon les standarts du w3c temps au point de vue css que html. 
Ajout d'un sytheme permetant l'auto-impression des documents gener�s

#####################################################
changelog 1.1.3--> 1.1.4
	 

Chasse aux bugs
Meilleure gestion des fichiers de language
Possibilit� d'imprimer les factures par lot
Possibilit� de generer les factures par lot
Integration des fichiers de language nl.php et en.php (merci � Toine e Cass pour leur traductions respectives.)





#####################################################

changelog 1.1.2--> 1.1.3

#chasse aux bugs
#Modification de la methode de cr�ation des bon, facture et devis en pdf ,il sont a present stock� temporairement sur le serveur et effac� par la suite.
#Ajout d'un module permettant de gerer les utilisateur de Factux et ainssi leur donner les droits sur tout ou partie du facturier
#Amelioration de la mailing liste avec l'ajout d'un editeur hml en ligne (disponible seulement avec mozilla ou mozilla-firefox)
#ajout de la possibilit�e d'envoyer les document par mail au format pdf.
#Le client peut a present etre modifi� apres la cr�ation du bon
#amelioration de l'installeur
#Possibilit�e de voir les statistiques des ann�es pr�cedentes
#a  jout d'un favicon (juste pour faire joli ;))
# Ajout d'un module de lot (principalement pour les commercants horeca soumit � la tracabilit�e.
Ce module n'est pas actif par default.
#ajout d'un calendrier en javascrip pour un facilit�e de prise de date.



Changelog 1 -->1.1

#  chasse aux bugs .
# Ajout de gifs et autre images afin d'embellir l'interface.
# Modification des styles css et ajout de classes dans les tableaux afin de facilit� la lisibilit�e des tableaux. 
# Ajout d'un nouveau module de statistiques permettant de voir l'evolution du chiffre d'affaire d'un client par mois. (ok)
# Achevement de l'internationalisation de l'interface (et publication du fichier language afin de de demander des contribution au niveau de la traduction).(50% effectu�)
# Ajout d'un module de devis. 
# Possibilit�e d'�diter et de suprimer les d�penses. )
# Meilleur gestion des fournisseurs dans les fichier de d�pense. 
# La version 1.1 devrait etre compatible avec les serveur qui sont sur register_global Off (mouveau standart d'instalation de php). 
# Integration d'un modules de backup et restauration des bases de donn�es. 
# Amelioration de l'installeur avec verification des droits d'ecriture dans les fichiers necessaires.Propose aussi l'upload du logo de l'entreprise(ok)
# Int�gration d'un menu dropdown avec image en lieu et place des icones atuels. 
# Ajout d'un module permettant aux clients de voir et d'imprimer leus factures, devis et bons de commande en ligne. 
# Ajout d'une mailing liste bas�e sur les adresses email des client. permetant par exemple d' envoyer vos promos ou fermetures anuelles � vos cients. 


Changelog beta -->1 stable

Eradication feroce des bugs
amelioration de l'interface de plusieurs fichiers pour confort d'utilisation
Ajout d'un indicateur de version
Notification en javascript pour les confirmation d'effacement et de payement de bons et factures
Amelioration de l'interface des bons de commande
Amlioration de l'installeur
lun avr 26 21:03:30 CEST 2004#

###########################################

License


# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.

