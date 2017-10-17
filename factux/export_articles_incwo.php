<?php

/*
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 */
require_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
include_once("ean13.php");

//display PHP errors
error_reporting(E_ALL);

//SQL
$columns = array('num', 'name', 'classification', 'categorie', 'prix_ttc_part', 'stock_disponible', 'code_barre');
$sql = "SELECT num,
    CONCAT(article, ', ', variete, ', ', taille, ', ', contenance, ', ', categorie) AS name,    
    'produit' as classification,
    categorie,
    prix_ttc_part,
    stock,
    300000000000 + num AS 'code_barre'
    FROM " . $tblpref . "article, " . $tblpref . "categorie
    WHERE actif != 'non'
    AND " . $tblpref . "article.cat = " . $tblpref . "categorie.id_cat";
$req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());

//output
$today = date("Y-m-d-H-i-s");
$filename = "plantes-incwo-".$today.".csv";
$fp = fopen('php://output', 'w');

//HTTP header
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

//first line
fputcsv($fp, $columns);

//data
while ($row = mysql_fetch_array($req, MYSQL_ASSOC)) {
    $values = array_values($row);
    
    //compute control key
    $values[6] = ean13_check_digit($values[6]);
    
    fputcsv($fp, $values);
}

fclose($fp);
