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
 * File Name: fact_multi.php
 * 	enregistrement de donn�es de la facture
 * 
 * * * Version:  1.1.5
 * * * * Modified: 23/07/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
require_once("include/verif.php");
include_once("include/head.php");
include_once("include/config/common.php");
include_once("include/config/var.php");
include_once("include/language/$lang.php");
include_once("include/headers.php");
include_once("include/finhead.php");

$acompte=isset($_POST['acompte'])?$_POST['acompte']:"";
$date_deb=isset($_POST['date_deb'])?$_POST['date_deb']:"";
list($jour_deb, $mois_deb,$annee_deb) = preg_split('/\//', $date_deb, 3);
$date_fin=isset($_POST['date_fin'])?$_POST['date_fin']:"";
list($jour_f, $mois_f,$annee_f) = preg_split('/\//', $date_fin, 3);
$date_fact=isset($_POST['date_fact'])?$_POST['date_fact']:"";
list($jour_fact, $mois_fact,$annee_fact) = preg_split('/\//', $date_fact, 3);
$annee_fac=isset($_POST['annee_fac'])?$_POST['annee_fac']:"";
$coment=isset($_POST['coment'])?$_POST['coment']:"";
$debut = "$annee_deb-$mois_deb-$jour_deb" ;
$fin = "$annee_f-$mois_f-$jour_f" ;
$date_fact ="$annee_fact-$mois_fact-$jour_fact";
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

if($date_deb==''|| $date_fin=='' || $date_fact=='' )
{
$message= "<center><h1>$lang_oubli_champ</h1>";
include('form_facture.php');
exit;
}
foreach($_POST[client] as $client) 
{

$sql = " SELECT * From " . $tblpref ."client WHERE num_client = $client ";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());


while($data = mysql_fetch_array($req))
    {
		$nom = $data['nom'];
		$nom2 = $data['nom2'];
		}

$sql = "SELECT * FROM " . $tblpref ."bon_comm 
		 	 	 WHERE client_num = '".$client."' 
				 AND " . $tblpref ."bon_comm.date >= '".$debut."' 
				 and " . $tblpref ."bon_comm.date <= '".$fin."' 
				 and fact = 'ok'";

$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
$p=0;
while($data = mysql_fetch_array($req))
    {
		$fact = $data['fact'];
		$p= $p +1;
		}
$guy=count($data);		
if($p !='0')
{
echo "<center><h1>$nom $lang_err_fact</h1></center>  ";
$error = '1';
}else{
$error = '0';
}

$sql = " SELECT SUM(tot_htva), SUM(tot_tva) 
		 FROM " . $tblpref ."bon_comm
		 WHERE " . $tblpref ."bon_comm.client_num = '".$client."'
		 AND " . $tblpref ."bon_comm.date >= '".$debut."' 
		 and " . $tblpref ."bon_comm.date <= '".$fin."'";

  $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
$data = mysql_fetch_array($req);
		$total_htva = $data['SUM(tot_htva)'];
		$total_tva = $data['SUM(tot_tva)'];
		$total_ttc = $total_htva + $total_tva ;
if($total_htva=='')
{
echo "<center><h1>$nom $lang_err_fact_2</h1></center> ";
$error2 = '1';

}else{
$error2 = '0';
}

//on recherche le numero de la facture cr�e
$sql = "SELECT MAX(num) As Maxi FROM " . $tblpref ."facture";
$result = mysql_query($sql) or die('Erreur');
$num = mysql_result($result, 'Maxi');
$num = $num + 1 ;

if($error !='1'and $error2 != '1'){
//On afiche le resultat
//echo "Facture $num cr��e pour $nom $nom2"; 
//nouvelle methode
$sql = " SELECT num_bon 
		FROM " . $tblpref ."bon_comm 
		 WHERE " . $tblpref ."bon_comm.client_num = '".$client."' 
		 AND " . $tblpref ."bon_comm.date >= '".$debut."' 
		 and " . $tblpref ."bon_comm.date <= '".$fin."'";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
unset($list_num);
while($data = mysql_fetch_array($req))
    {
		$list_num[] = $data['num_bon'];
		}
$list_num = serialize($list_num);
//on enregistre le contenu de la facture
$sql1 = "INSERT INTO " . $tblpref ."facture(acompte, coment, client, date_deb, date_fin, date_fact, total_fact_h, total_fact_ttc, list_num) 
VALUES ('$acompte', '$coment', '$client', '$debut', '$fin', '$date_fact', '$total_htva', '$total_ttc', '$list_num')";
mysql_query($sql1) or die('Erreur SQL1 !<br>'.$sql1.'<br>'.mysql_error());
echo "<center><h2> Facture crenregistr�e pour $nom $nom2</h2><br>";		
$sql2 = "UPDATE " . $tblpref ."bon_comm SET fact='ok' WHERE " . $tblpref ."bon_comm.client_num = '".$client."' AND " . $tblpref ."bon_comm.date >= '".$debut."' and " . $tblpref ."bon_comm.date <= '".$fin."'";
mysql_query($sql2) or die('Erreur SQL2 !<br>'.$sql2.'<br>'.mysql_error());
}
}

include_once("include/bas.php");
?> 