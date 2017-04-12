<?php

/*
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 */
require_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");

//display PHP errors
error_reporting(E_ALL);

//SQL
$columns = array('num', 'article', 'variete', 'taille', 'contenance', 'prix_ttc_part', 'prix_htva_part', 'categorie', 'stock');
$sql = "SELECT ".implode(",", $columns)
        . " FROM " . $tblpref . "article, " . $tblpref . "categorie"
        . " WHERE actif != 'non' AND"
        . " " . $tblpref . "article.cat = " . $tblpref . "categorie.id_cat";
mysql_query("set names 'utf8'");
$req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());

//output
$filename = "plantes.csv";
$fp = fopen('php://output', 'w');

//HTTP header
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

//first line
fputcsv($fp, $columns);

//data
while ($row = mysql_fetch_array($req, MYSQL_ASSOC)) {
    fputcsv($fp, array_values($row));
}

fclose($fp);