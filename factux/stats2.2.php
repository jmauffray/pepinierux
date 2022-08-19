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
require_once("include/config/common.php");
require_once("include/head.php");
require_once("include/language/$lang.php");
require_once("include/nb.php");
echo '<link rel="stylesheet" type="text/css" href="include/style.css">';
echo'<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico" >';
?>
<?php
$annee = date("Y");
$mois = date("m");
$mois_1=isset($_POST['mois_1'])?$_POST['mois_1']:"";
$annee_1=isset($_POST['annee_1'])?$_POST['annee_1']:"";

?>
<form action="stats2.2.php" method="post">
<select name="mois_1">
<option value="1">Janvier</option>
<option value="2">Février</option>
<option value="3">Mars</option>
<option value="4">Avril</option>
<option value="5">Mai</option>
<option value="6">Juin</option>
<option value="7">Juillet</option>
<option value="8">Août</option>
<option value="9">Septembre</option>
<option value="10">Octobre</option>
<option value="11">Novembre</option>
<option value="12">Decembre</option>
</select>
<select name="annee_1">
<option value="2025">2025</option>
<option value="2024">2024</option>
<option value="2023">2023</option>
<option value="2022">2022</option>
<option value="2021">2021</option>
<option value="2020">2020</option>
<option value="2019">2019</option>
<option value="2018">2018</option>
<option value="2017">2017</option>
<option value="2016">2016</option>
<option value="2015">2015</option>
<option value="2014">2014</option>
<option value="2013">2013</option>
<option value="2012">2012</option>
<option value="2011">2011</option>
<option value="2010">2010</option>
<option value="2009">2009</option>
<option value="2008">2008</option>
</select>
<button type="submit">Envoyer</button>
<?php 


if ($mois_1=='') {
 $mois_1= $mois ;
} 
if ($annee_1=='') { 
 $annee_1= $annee ; 
}
 ?>
<?php

$sql = "SELECT num_client FROM " . $tblpref ."client";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
$nb = mysql_num_rows($req);
echo "<h2>Statistiques du mois $mois/$annee par client</font><br><hr>";
echo "<center><table>"; 
?>
<tr><td class="titretableau">Client
    <td class="titretableau"><?php echo "$lang_total_mois $mois_1/$annee_1"; ?></tr>
<?php
//pour le total
$sql = "SELECT SUM(tot_htva)FROM " . $tblpref ."bon_comm WHERE MONTH(date)= $mois_1 AND Year(date)=$annee_1 ";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
$data = mysql_fetch_array($req);
$tot2 = $data['SUM(tot_htva)'];

for ($i=1;$i<=$nb;$i++)
{

$sql = "SELECT SUM(tot_htva), nom FROM  " . $tblpref ."bon_comm RIGHT JOIN " . $tblpref ."client on client_num = num_client WHERE client_num =\"$i\" AND Year(date)=$annee_1 AND MONTH(date)=$mois_1 GROUP BY nom";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
$data = mysql_fetch_array($req);
$nom = $data['nom'];
$tot = $data['SUM(tot_htva)'];

?>
<tr><td class='<?php echo couleur_alternee (); ?>'><?php echo $nom; ?></td>
<td class='td2montant'><?php echo montant_financier($tot); ?></td></tr>
<?php
}

?>
<tr><td class="totallibelle"><?php echo $lang_total; ?></td>
<td class='totalmontant'><? echo montant_financier($tot2); ?></td></tr>
</table><br>

<?php
include("graph2_client.php");
echo "<br><hr>";
require_once("include/bas.php"); 
 ?> 