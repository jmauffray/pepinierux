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
 * File Name: edit_bon_suite.php
 * 	
 * 
 * * * Version:  1.1.5
 * * * * Modified: 23/07/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
include_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
require_once("include/configav.php");
$article=isset($_POST['article'])?$_POST['article']:"";
$nom=isset($_POST['nom'])?$_POST['nom']:"";
$num_bon=isset($_POST['num_bon'])?$_POST['num_bon']:"";
$quanti=isset($_POST['quanti'])?$_POST['quanti']:"";
$remise=isset($_POST['remise'])?$_POST['remise']:"";
$prix_remise=isset($_POST['prix_remise'])?$_POST['prix_remise']:"";
$volume_pot=isset($_POST['volume_pot'])?$_POST['volume_pot']:"";
$num_lot=isset($_POST['lot'])?$_POST['lot']:"";
$type=isset($_POST['type'])?$_POST['type']:"";
$prix_htva='prix_htva';
$taux_tva='taux_tva';
if( $type=='particulier' )
  {
    $prix_htva='prix_htva_part';
    $taux_tva='taux_tva_part';
  }
else if( $quanti > 14 )
  {
    $prix_htva='prix_htva_gros';
  }

//on recupere le prix htva		
$sql2 = "SELECT $prix_htva FROM " . $tblpref ."article WHERE num = $article";
$result = mysql_query($sql2) or die('Erreur SQL1 !<br>'.$sql2.'<br>'.mysql_error());
$prix_article = mysql_result($result, '$prix_htva');

//on recupere le taux de tva
$sql3 = "SELECT $taux_tva FROM " . $tblpref ."article WHERE num = $article";
$result = mysql_query($sql3) or die('Erreur SQL2 !<br>'.$sql3.'<br>'.mysql_error());
$taux_tva_res = mysql_result($result, '$taux_tva');

//on recupere le taux de tva
$sql3 = "SELECT conditionnement FROM " . $tblpref ."article WHERE num = $article";
$result = mysql_query($sql3) or die('Erreur SQL2 !<br>'.$sql3.'<br>'.mysql_error());
$conditionnement = mysql_result($result, 'conditionnement');

//premiere utilisation de la remise, on la calcule si nécessaire
if( ($remise == 0) && ($prix_remise != '') ) {
  $thePrix = 0;
  if( $type == 'particulier' )
  {
    $thePrix = $prix_article * (1 + $taux_tva_res / 100);
  }
  else
  {
    $thePrix = $prix_article;
  }

  if( $thePrix !=  0 )
  {
    $remise = (1 - $prix_remise / $thePrix ) * 100;
  }
}

//prise en compte du volume
if( $prix_remise == '' )
  {
    $prix_article += ($volume_pot * 0.15);
  }

$montant_u_htva = $prix_article  * (100-$remise)/100 ;
$total_htva = $montant_u_htva * $quanti;
$mont_tva = $total_htva / 100 * $taux_tva_res ;

////conditionnement
if($volume_pot > 0)
  {
    $conditionnement = "motte en pot";
  }

//inserer les données dans la table du contenu des bons.
mysql_select_db($db) or die ("Could not select $db database");
$sql1 = "INSERT INTO " . $tblpref ."cont_bon(num_lot, quanti, remise, volume_pot, conditionnement, article_num, bon_num, tot_art_htva, to_tva_art, p_u_jour, p_u_jour_net) 
VALUES ('$num_lot', '$quanti', '$remise', '$volume_pot', '$conditionnement', '$article', '$num_bon', '$total_htva', '$mont_tva', '$prix_article', '$montant_u_htva')";
mysql_query($sql1) or die('Erreur SQL3 !<br>'.$sql1.'<br>'.mysql_error());


include_once("edit_bon.php");