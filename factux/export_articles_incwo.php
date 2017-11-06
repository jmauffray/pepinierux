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
$columns = array('Référence',
            'Nom du produit',
            'Classification',
            'Catégorie de produit',
            'Prix de vente TTC',
            'Taux de tva',
            'Stock entrepot 1',
            'Code-barre EAN');

$sql = "SELECT num as 'Référence',
    CONCAT(article, ', ', variete, ', ', taille, ', ', contenance, ', ', categorie) AS 'Nom du produit',    
    'produit' as Classification,
    categorie as 'Catégorie de produit',
    prix_ttc_part as 'Prix de vente TTC',
    taux_tva_part as 'Taux de tva',
    stock as 'Stock entrepot 1',
    300000000000 + num AS 'Code-barre EAN'
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
    $values[7] = ean13_check_digit($values[7]);
    
    fputcsv($fp, $values);
}

fclose($fp);
