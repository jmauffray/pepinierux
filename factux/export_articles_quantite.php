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
$columns = array('num',
    'article',
    'variete',
    'groupe_varietal',
    'taille', 
    'conditionnement',
    'contenance',
    'REPLACE(FORMAT(prix_ttc_part, 2), \'.\', \',\')',
    'REPLACE(FORMAT(prix_htva, 2), \'.\', \',\')',
    'categorie',
    'localisation',
    'prix_achat',
    'REPLACE(stock,".00","")',
    'REPLACE(stock_disponible,".00","")');
$columnsHeader = array('num',
    'article',
    'variete',
    'groupe_varietal',
    'taille', 
    'conditionnement',
    'contenance',
    'prix_ttc_part',
    'prix_htva',
    'categorie',
    'localisation',
    'prix_achat',
    'stock',
    'stock_disponible');
$sql = "SELECT ".implode(",", $columns)
        . " FROM " . $tblpref . "article, " . $tblpref . "categorie"
        . " WHERE actif != 'non' AND stock > 0 AND"
        . " " . $tblpref . "article.cat = " . $tblpref . "categorie.id_cat "
        . " ORDER by article, variete, taille + 0";
mysql_query("set names 'utf8'");
$req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());

//output
$today = date("Y-m-d-H-i-s");
$filename = "plantes-".$today.".csv";
$fp = fopen('php://output', 'w');

//HTTP header
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

//first line
fputcsv($fp, $columnsHeader);

//data
while ($row = mysql_fetch_array($req, MYSQL_ASSOC)) {
    fputcsv($fp, array_values($row));
}

fclose($fp);
