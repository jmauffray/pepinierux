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
 * * Version:  1.1.5
 * * * * Modified: 23/07/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
require_once("include/verif.php");
require_once("include/config/common.php");
require_once("include/language/$lang.php");

$tot_ht=isset($_POST['tot_ht'])?$_POST['tot_ht']:"";
$tot_tva=isset($_POST['tot_tva'])?$_POST['tot_tva']:"";
$bon_num=isset($_POST['bon_num'])?$_POST['bon_num']:"";
$coment=isset($_POST['coment'])?$_POST['coment']:"";
mysql_select_db($db) or die ("Could not select $db database");

$sql2 = "UPDATE " . $tblpref ."bon_comm SET tot_htva='".$tot_ht."'  WHERE num_bon = $bon_num";
mysql_query($sql2) OR die("<p>Erreur Mysql2<br/>$sql2<br/>".mysql_error()."</p>");
$sql3 = "UPDATE " . $tblpref ."bon_comm SET tot_tva='".$tot_tva."'  WHERE num_bon = $bon_num";
mysql_query($sql3) OR die("<p>Erreur Mysql2<br/>$sql3<br/>".mysql_error()."</p>");
$sql4 = "UPDATE " . $tblpref ."bon_comm SET coment='".$coment."'  WHERE num_bon = $bon_num";
mysql_query($sql4) OR die("<p>Erreur Mysql2<br/>$sql4<br/>".mysql_error()."</p>");
$message= "<center><h2>$lang_enre</h2></center>";

include("form_commande.php");
 ?> 