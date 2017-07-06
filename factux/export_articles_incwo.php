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

function ean13_check_digit($digits) {
//first change digits to a string so that we can access individual numbers
    $digits = (string) $digits;
// 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
    $even_sum = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
// 2. Multiply this result by 3.
    $even_sum_three = $even_sum * 3;
// 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
    $odd_sum = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
// 4. Sum the results of steps 2 and 3.
    $total_sum = $even_sum_three + $odd_sum;
// 5. The check character is the smallest number which, when added to the result in step 4,  produces a multiple of 10.
    $next_ten = (ceil($total_sum / 10)) * 10;
    $check_digit = $next_ten - $total_sum;
    return $digits . $check_digit;
}
