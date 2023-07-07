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

//get num modifie
$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : "";
$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : "";

//get info from facture
$sql = "SELECT 'Num', 'Article', 'Variete', 'Origine', 'Quantite vendu', 'Total HT', 'Prix achat', 'Stock', 'Stock disponible', 'Contenance', 'Conditionnement'
UNION ALL SELECT " . $tblpref . "article.num, " . $tblpref . "article.article, " . $tblpref . "article.variete, " . $tblpref . "categorie.categorie, SUM( " . $tblpref . "cont_bon.quanti ), SUM( " . $tblpref . "cont_bon.tot_art_htva ), " . $tblpref . "article.prix_achat, " . $tblpref . "article.stock, " . $tblpref . "article.stock_disponible, " . $tblpref . "article.contenance, " . $tblpref . "article.conditionnement 
FROM " . $tblpref . "cont_bon 
LEFT JOIN " . $tblpref . "article ON " . $tblpref . "cont_bon.article_num = " . $tblpref . "article.num LEFT JOIN " . $tblpref . "categorie ON " . $tblpref . "categorie.id_cat = " . $tblpref . "article.cat
WHERE " . $tblpref . "cont_bon.bon_num
IN ( SELECT num_bon FROM " . $tblpref . "bon_comm 
WHERE DATE BETWEEN '$date_from' AND '$date_to' AND fact = 'ok' ) 
GROUP BY article,cat,num";

$req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());

//output
$filename = "factux-ventes-".$date_from."_".$date_to.".csv";
$fp = fopen('php://output', 'w');

//HTTP header
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

//data
while ($row = mysql_fetch_array($req, MYSQL_ASSOC)) {
    fputcsv($fp, array_values($row));
}

fclose($fp);
