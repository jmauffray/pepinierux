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
include_once("bon_suite_util.php");

$article=isset($_POST['article'])?$_POST['article']:"";
$nom=isset($_POST['nom'])?$_POST['nom']:"";
$num_bon=isset($_POST['num_bon'])?$_POST['num_bon']:"";
$quanti=isset($_POST['quanti'])?$_POST['quanti']:"";
$remise=isset($_POST['remise'])?$_POST['remise']:"";
$prix_remise=isset($_POST['prix_remise'])?$_POST['prix_remise']:"";
$volume_pot=isset($_POST['volume_pot'])?$_POST['volume_pot']:"";
$num_lot=isset($_POST['lot'])?$_POST['lot']:"";
$type=isset($_POST['type'])?$_POST['type']:"";

 addArticleInBon(
        $num_bon, $quanti, $remise, $prix_remise, $volume_pot, $article, $type, $lot1);

include_once("edit_bon.php");
