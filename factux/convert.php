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
 * File Name: convert.php
 * 	conversion des devis en bon de commande
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
include_once("include/headers.php");
include_once("include/finhead.php");
include_once("include/utils.php");

$num_dev=isset($_GET['num_dev'])?$_GET['num_dev']:"";
$jour = date("d");
$mois = date("m");
$annee = date("Y");
//on recpere les donnee de devis
$sql0 = "SELECT * FROM " . $tblpref ."devis WHERE num_dev = $num_dev";
$req = mysql_query($sql0) or die('Erreur SQL !<br>'.$sql0.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
    {
		$num_dev = $data['num_dev'];
		$client_num = $data['client_num'];
		$date = $data['date'];
		$tot_htva = $data['tot_htva'];
		$tot_tva = $data['tot_tva'];
		$coment = mysql_real_escape_string($data['coment']);
		}
		//on les reinjecte dans la base bon_comm

$sql1 = "INSERT INTO " . $tblpref ."bon_comm ( client_num, date, tot_htva, tot_tva, coment ) VALUES ( $client_num, '$annee-$mois-$jour', $tot_htva, $tot_tva, '$coment' )";

mysql_query($sql1) or die('Erreur SQL !<br>'.$sql1.'<br>'.mysql_error());

$sql2 = "UPDATE " . $tblpref ."devis SET resu='ok' WHERE num_dev= $num_dev";
mysql_query($sql2) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
//touver le dernier enregistrement pour le numero de bon
$sql = "SELECT MAX(num_bon) As Maxi FROM " . $tblpref ."bon_comm";
$result = mysql_query($sql) or die('Erreur');
$max = mysql_result2($result, 'Maxi');

$sql3 = "SELECT * FROM " . $tblpref ."cont_dev WHERE dev_num = $num_dev";
$req = mysql_query($sql3) or die('Erreur SQL !<br>'.$sql3.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
    {
		$article_num = $data['article_num'];
		$quanti = $data['quanti'];
		$remise = $data['remise'];
		$volume_pot = $data['volume_pot'];
		$conditionnement = $data['conditionnement'];
		$tot_art_htva = $data['tot_art_htva'];
		$to_tva_art = $data['to_tva_art'];
		$p_u_jour = $data['p_u_jour'];
		$p_u_jour_net = $p_u_jour * (100-$remise) / 100;
		
$sql4 = "INSERT INTO " . $tblpref ."cont_bon(bon_num, article_num, quanti, remise, volume_pot, conditionnement, tot_art_htva, to_tva_art, p_u_jour, p_u_jour_net) 
VALUES ('$max', '$article_num', '$quanti', '$remise', '$volume_pot', '$conditionnement', '$tot_art_htva', '$to_tva_art', '$p_u_jour', '$p_u_jour_net')";
mysql_query($sql4) or die('Erreur SQL !<br>'.$sql4.'<br>'.mysql_error());
//on decremente le stock
$sql12 = "UPDATE `" . $tblpref ."article` SET `stock` = (stock - $quanti) WHERE `num` = '$article_num'";
mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());

}
$message= "$lang_dev_cov <br><form action=\"fpdf/bon_pdf.php\" method=\"post\" target= \"_blank\" ><input type=\"hidden\" name=\"num_bon\" value=\"$max\" /><input type=\"hidden\" name=\"user\" VALUE=\"adm\"><input type=\"image\" src=\"image/printer.gif\" alt=\"imprimer\" /></form>";

include_once("lister_devis.php");
 ?> 