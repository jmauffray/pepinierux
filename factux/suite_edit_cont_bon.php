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
 * File Name: fckconfig.js
 * 	Editor configuration settings.
 * 
 * * * Version:  1.1.5
 * * * * Modified: 23/07/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
require_once("include/verif.php");
include_once("include/config/common.php");
echo '<link rel="stylesheet" type="text/css" href="include/style.css">';
echo'<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico" >';
$quanti=isset($_POST['quanti'])?$_POST['quanti']:"";
$remise=isset($_POST['remise'])?$_POST['remise']:"";
$prix_remise=isset($_POST['prix_remise'])?$_POST['prix_remise']:"";
$volume_pot=isset($_POST['volume_pot'])?$_POST['volume_pot']:"";
$num_cont=isset($_POST['num_cont'])?$_POST['num_cont']:"";
$bon_num=isset($_POST['bon_num'])?$_POST['bon_num']:"";
$article=isset($_POST['article'])?$_POST['article']:"";
$num_lot=isset($_POST['num_lot'])?$_POST['num_lot']:"";

//pro or part
$type=isset($_POST['type'])?$_POST['type']:"";
$prix_htva='prix_htva';
$taux_tva='taux_tva';
if( $type=='particulier' )
  {
    $taux_tva='taux_tva_part';
    $prix_htva='prix_htva_part';
  }

$sql = "SELECT $prix_htva,$taux_tva, conditionnement FROM " . $tblpref ."article WHERE  " . $tblpref ."article.num = $article";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
  {
    $prix_article = $data[$prix_htva];
    $taux_tva_res = $data[$taux_tva];
    $conditionnement = $data['conditionnement'];
  }

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
    $prix_article += ($volume_pot * 0.17);
  }

$montant_u_htva = $prix_article * (100-$remise) / 100;
$tot_htva = $quanti * $montant_u_htva;
$tot_tva = $tot_htva / 100 * $taux_tva_res ;

/////////////////
$sql = "SELECT quanti, article_num from " . $tblpref ."cont_bon WHERE num = '".$num_cont."'";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
$data = mysql_fetch_array($req);
$quantiplus = $data['quanti'];
$artiplus = $data['article_num'];
//restore quantite before bon
$sql11 = "UPDATE `" . $tblpref ."article` SET `stock` = (stock + $quantiplus) WHERE `num` = '$artiplus'";
mysql_query($sql11) or die('Erreur SQL11 !<br>'.$sql11.'<br>'.mysql_error());
$sql11 = "UPDATE `" . $tblpref ."article` SET `stock_disponible` = (stock_disponible + $quantiplus) WHERE `num` = '$artiplus'";
mysql_query($sql11) or die('Erreur SQL11 !<br>'.$sql11.'<br>'.mysql_error());

//update quantite with new quantite
$sql12 = "UPDATE `" . $tblpref ."article` SET `stock` = (stock - $quanti) WHERE `num` = '$article'";
mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());
$sql12 = "UPDATE `" . $tblpref ."article` SET `stock_disponible` = (stock_disponible - $quanti) WHERE `num` = '$article'";
mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());

////conditionnement
if($volume_pot > 0)
  {
    $conditionnement = "motte en pot";
  }

////////////////
$sql2 = "UPDATE " . $tblpref ."cont_bon 
SET p_u_jour='".$prix_article."', p_u_jour_net='".$montant_u_htva."', num_lot='".$num_lot."', quanti='".$quanti."', remise='".$remise."', volume_pot='".$volume_pot."', conditionnement='".$conditionnement."', article_num='".$article."', tot_art_htva='".$tot_htva."', to_tva_art='".$tot_tva."'  
WHERE num = '".$num_cont."'";
mysql_query($sql2) OR die("<p>Erreur Mysql<br/>$sql2<br/>".mysql_error()."</p>");
  
$num_bon=$bon_num;

 include_once("edit_bon.php");
 ?> 