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
$quanti=isset($_POST['quanti'])?$_POST['quanti']:"";
$remise=isset($_POST['remise'])?$_POST['remise']:"";
$prix_remise=isset($_POST['prix_remise'])?$_POST['prix_remise']:"";
$volume_pot=isset($_POST['volume_pot'])?$_POST['volume_pot']:"";
$num_cont=isset($_POST['num_cont'])?$_POST['num_cont']:"";
$dev_num=isset($_POST['dev_num'])?$_POST['dev_num']:"";
$article=isset($_POST['article'])?$_POST['article']:"";

//on recupere le client
$sql2 = "SELECT client_num FROM " . $tblpref ."devis WHERE num_dev = $dev_num";
$result = mysql_query($sql2) or die('Erreur SQL1 !<br>'.$sql2.'<br>'.mysql_error());
$client_num = mysql_result2($result, 'client_num');
//on recupere le type de client
$sql2 = "SELECT type FROM " . $tblpref ."client WHERE num_client = $client_num";
$result = mysql_query($sql2) or die('Erreur SQL1 !<br>'.$sql2.'<br>'.mysql_error());
$type = mysql_result2($result, 'type');
//part or pro
$prix_htva = 'prix_htva';
$taux_tva = 'taux_tva';
if( $type=='particulier')
  {
    $prix_htva = 'prix_htva_part';
    $taux_tva = 'taux_tva_part';
  }
else if( $quanti > 14 )
  {
    $prix_htva='prix_htva_gros';
  }

$sql = "SELECT $prix_htva, $taux_tva, conditionnement FROM " . $tblpref ."article WHERE  " . $tblpref ."article.num = $article";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
    {
		$prix_article = $data[$prix_htva];
		$taux_tva_res = $data[$taux_tva];
		$conditionnement = $data['conditionnement'];
		//echo " $prix_ht <br>";
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
    $prix_article += ($volume_pot * 0.15);
  }

$montant_u_htva = $prix_article * (100-$remise)/100;
$tot_htva = $quanti * $montant_u_htva;
$tot_tva = $tot_htva / 100 * $taux_tva_res ;

////conditionnement
if($volume_pot > 0)
  {
    $conditionnement = "motte en pot";
  }

mysql_select_db($db) or die ("Could not select $db database");
$sql2 = "UPDATE " . $tblpref ."cont_dev SET p_u_jour='".$prix_article."', p_u_jour_net='".$montant_u_htva."', quanti='".$quanti."', remise='".$remise."', volume_pot='".$volume_pot."', conditionnement='".$conditionnement."', article_num='".$article."', tot_art_htva='".$tot_htva."', to_tva_art='".$tot_tva."'  WHERE num = '".$num_cont."'";
mysql_query($sql2) OR die("<p>Erreur Mysql<br/>$sql2<br/>".mysql_error()."</p>");
  
 $num_dev = $dev_num ;
  include_once("edit_devis.php");
 ?> 