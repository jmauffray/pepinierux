<?php 
/*
 * Factux le facturier libre
 * Copyright (C) 2003-2004 Guy Hendrickx
 * 
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 * 
 * For further information visit:
 * 		http://factux.sourceforge.net
 * 
 * File Name: article_update.php
 * 	saisie de la modification d'un article
 * 
 * * Version:  1.1.5
 * * * * Modified: 23/07/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
require_once("include/verif.php");
include_once("include/config/common.php");

include_once("include/language/$lang.php");
$num=isset($_POST['num'])?$_POST['num']:"";
$article1=isset($_POST['article'])?$_POST['article']:"";
$prix=isset($_POST['prix'])?$_POST['prix']:"";
$prix_gros=isset($_POST['prix_gros'])?$_POST['prix_gros']:"";
//$prix_part=isset($_POST['prix_part'])?$_POST['prix_part']:"";
$prix_part_ttc=isset($_POST['prix_part_ttc'])?$_POST['prix_part_ttc']:"";
$tva=isset($_POST['tva'])?$_POST['tva']:"";
$tva_part=isset($_POST['tva_part'])?$_POST['tva_part']:"";
$prix_part = ($prix_part_ttc * 100 / (100 + $tva_part));
$stock=isset($_POST['stock'])?$_POST['stock']:"";
$categorie=isset($_POST['categorie'])?$_POST['categorie']:"";
$taille=isset($_POST['taille'])?$_POST['taille']:"";
$variete1=isset($_POST['variete'])?$_POST['variete']:"";
$contenance1=isset($_POST['contenance'])?$_POST['contenance']:"";
$phyto1=isset($_POST['phyto'])?$_POST['phyto']:"";
$conditionnement1=isset($_POST['conditionnement'])?$_POST['conditionnement']:"";
$id=0;

mysql_select_db($db) or die ("Could not select $db database");

//fix special char for sql
$article=mysql_real_escape_string($article1);
$conditionnement=mysql_real_escape_string($conditionnement1);
$variete=mysql_real_escape_string($variete1);
$contenance=mysql_real_escape_string($contenance1);
$phyto=mysql_real_escape_string($phyto1);
                    
$Submit = $_POST['Submit'];
if( $Submit == 'Modifier')
  {
    $sql2 = "UPDATE " . $tblpref ."article SET"
            . " `prix_htva`='".$prix."',"
            . "`prix_htva_gros`='".$prix_gros."',"
            . "`taux_tva`='".$tva."',"
            . "`taux_tva_part`='".$tva_part."',"
            . "`prix_htva_part`='".$prix_part."',"
            . "`prix_ttc_part`='".$prix_part_ttc."',"
            . "`stock`='".$stock."',"
            . "`cat`='".$categorie."',"
            . "`taille`='".$taille."',"
            . "`article`='".$article."',"
            . "`conditionnement`='".$conditionnement."',"
            . "`variete`='".$variete."',"
            . "`contenance`='".$contenance."',"
            . "`phyto`='".$phyto."'"
            . " WHERE num ='".$num."'"
            . " LIMIT 1 ";
    $id = $num;
  }
 else
   {
     $sql2 = "INSERT INTO " . $tblpref ."article(article, prix_htva,prix_htva_gros, taux_tva, prix_ttc_part, prix_htva_part, taux_tva_part, uni, stock, cat, taille, conditionnement, variete, contenance, phyto)"
             . " VALUES ('$article', '$prix','$prix_gros', '$tva','$prix_part_ttc', '$prix_part', '$tva_part', 'pcs', '$stock', '$categorie', '$taille', '$conditionnement', '$variete', '$contenance', '$phyto')";
   }
mysql_query($sql2) OR die("<p>Erreur Mysql<br/>$sql2<br/>".mysql_error()."</p>");
if( $Submit != 'Modifier')
  {
    $id = mysql_insert_id();
  }
$message= "<h2>$lang_stock_jour : N° $id</h2><br><hr>";
include_once("lister_articles.php");
?> 