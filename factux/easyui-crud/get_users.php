<?php

include '../include/config/common.php';

//for pagination
$page = intval($_REQUEST['page']);
$row = intval($_REQUEST['rows']);
$from = ($page - 1) * $row;

//for search
$num = isset($_REQUEST['num']) ? mysql_real_escape_string($_REQUEST['num']) : '';
$article = isset($_REQUEST['article']) ? mysql_real_escape_string($_REQUEST['article']) : '';
$variete = isset($_REQUEST['variete']) ? mysql_real_escape_string($_REQUEST['variete']) : '';

if (empty($num)) {
    $whereSql = "UPPER(article) like UPPER('%$article%') AND UPPER(variete) like UPPER('%$variete%')";
} else {
    $whereSql = "num LIKE '$num'";
}

//get articles
$sql = "SELECT * FROM " . $tblpref ."article
LEFT JOIN " . $tblpref ."categorie ON " . $tblpref ."categorie.id_cat = " . $tblpref ."article.cat
WHERE actif != 'non'
AND " . $whereSql ." 
LIMIT $from, $row";

mysql_query("set names 'utf8'");
$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
$result1["rows"] = $result;

//count articles nb
$sql0="select count(*) from " . $tblpref ."article 
WHERE actif != 'non'
AND " . $whereSql;
    
$rs0 = mysql_query($sql0);
$row0 = mysql_fetch_row($rs0);
$result1["total"] = $row0[0];

//return json
echo json_encode($result1);

?>
