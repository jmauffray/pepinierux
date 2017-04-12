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
 * File Name: article_new.php
 * 	Enregistrement des nouveaux articles
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
echo '<link rel="stylesheet" type="text/css" href="include/style.css">';
echo'<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico" >';
$article=isset($_POST['article'])?$_POST['article']:"";
$uni="pcs";
$prix=isset($_POST['prix'])?$_POST['prix']:"";
$prix_gros=isset($_POST['prix_gros'])?$_POST['prix_gros']:"";
$taux_tva=isset($_POST['taux_tva'])?$_POST['taux_tva']:"";
//$prix_part=isset($_POST['prix_part'])?$_POST['prix_part']:"";
$prix_part_ttc=isset($_POST['prix_part_ttc'])?$_POST['prix_part_ttc']:"";
$taux_tva_part=isset($_POST['taux_tva_part'])?$_POST['taux_tva_part']:"";
$prix_part = ($prix_part_ttc * 100 / (100 + $taux_tva));
$commentaire=isset($_POST['commentaire'])?$_POST['commentaire']:"";
$stock=isset($_POST['stock'])?$_POST['stock']:"";
$categorie=isset($_POST['categorie'])?$_POST['categorie']:"";
$variete=isset($_POST['variete'])?$_POST['variete']:"";
$phyto=isset($_POST['phyto'])?$_POST['phyto']:"";
$taille=isset($_POST['taille'])?$_POST['taille']:"";
$conditionnement=isset($_POST['conditionnement'])?$_POST['conditionnement']:"";
$contenance=isset($_POST['contenance'])?$_POST['contenance']:"";
if($article=='' || $prix==''|| $taux_tva=='' || $uni=='' )
  {
    echo "<center><h1>$lang_oubli_champ";
    include('form_article.php');
    exit;
  }

mysql_select_db($db) or die ("Could not select $db database");
$sql1 = "INSERT INTO " . $tblpref ."article(article, prix_htva, prix_htva_gros, prix_ttc_part, taux_tva, prix_htva_part, taux_tva_part, commentaire, uni, stock, cat, taille, conditionnement, contenance, variete, phyto) VALUES ('$article', '$prix', '$prix_gros', '$prix_part_ttc', '$taux_tva','$prix_part', '$taux_tva_part', '$commentaire', '$uni', '$stock', '$categorie', '$taille', '$conditionnement', '$contenance', '$variete', '$phyto')";
mysql_query($sql1) or die('Erreur SQL !<br>'.$sql1.'<br>'.mysql_error());
$lastItemID = mysql_insert_id();
$message= "<center><h2>$lang_nouv_art<br>N° $lastItemID</h2>";
include("form_article.php");
include_once("include/bas.php");
?>