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
include_once("include/language/$lang.php");
include_once("include/utils.php");
?><html>
<head>

 <title><?php echo "$lang_factux" ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/style.css">
<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico" >
<?php
include_once("include/autocomplete-headers.php");
?>
</head>

<body onload="document.formu2.article.focus()">
<?php
include_once("include/autocomplete.php");
?>
<table width="760" border="0" class="page" align="center">
<tr>
<td class="page" align="center">
<?php
include_once("include/head.php");
?>
</td>
</tr>
<tr>
<td  class="page" align="center">
<?php 
$nom=isset($_POST['nom'])?$_POST['nom']:"";
$type=isset($_POST['type'])?$_POST['type']:"";
$num_dev=isset($_POST['num_dev'])?$_POST['num_dev']:"";
$quanti=isset($_POST['quanti'])?$_POST['quanti']:"";
$remise=isset($_POST['remise'])?$_POST['remise']:"";
$prix_remise=isset($_POST['prix_remise'])?$_POST['prix_remise']:"";
$volume_pot=isset($_POST['volume_pot'])?$_POST['volume_pot']:"";
$article=isset($_POST['article'])?$_POST['article']:"";

//on recupere le client
$sql2 = "SELECT client_num FROM " . $tblpref ."devis WHERE num_dev = $num_dev";
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

//on recupere le prix htva		
$sql2 = "SELECT $prix_htva FROM " . $tblpref ."article WHERE num = $article";
$result = mysql_query($sql2) or die('Erreur SQL1 !<br>'.$sql2.'<br>'.mysql_error());
$prix_article = mysql_result2($result, $prix_htva);

//on recupere le taux de tva
$sql3 = "SELECT $taux_tva FROM " . $tblpref ."article WHERE num = $article";
$result = mysql_query($sql3) or die('Erreur SQL2 !<br>'.$sql3.'<br>'.mysql_error());
$taux_tva_res = mysql_result2($result, $taux_tva);

//on recupere le conditionnement
$sql3 = "SELECT conditionnement FROM " . $tblpref ."article WHERE num = $article";
$result = mysql_query($sql3) or die('Erreur SQL2 !<br>'.$sql3.'<br>'.mysql_error());
$conditionnement = mysql_result2($result, 'conditionnement');

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
    $prix_article += ($volume_pot * $price_per_liter);
  }

$montant_u_htva = $prix_article * (100-$remise)/100;
$total_htva = $quanti * $montant_u_htva;
$mont_tva = $total_htva / 100 * $taux_tva_res ;

////conditionnement
if($volume_pot > 0)
  {
    $conditionnement = "motte en pot";
  }

//inserer les données dans la table du contenu des devis.
mysql_select_db($db) or die ("Could not select $db database");
$sql1 = "INSERT INTO " . $tblpref ."cont_dev(quanti, remise, volume_pot, conditionnement, article_num, dev_num, tot_art_htva, to_tva_art, p_u_jour, p_u_jour_net) VALUES ('$quanti', '$remise', '$volume_pot', '$conditionnement', '$article', '$num_dev', '$total_htva', '$mont_tva', '$prix_article', '$montant_u_htva')";
mysql_query($sql1) or die('Erreur SQL3 !<br>'.$sql1.'<br>'.mysql_error());
include ("form_editer_devis.php");
?><!-- InstanceEndEditable --> 
</td></tr>
</table>
<?php
include("help.php");
include_once("include/bas.php");
?>
</body>
<!-- InstanceEnd --></html>
