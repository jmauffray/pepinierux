<?php

include '../include/config/common.php';

//for pagination
$page = intval($_REQUEST['page']);
$row = intval($_REQUEST['rows']);
$from = ($page - 1) * $row;

//get articles
$sql = "SELECT * FROM " . $tblpref ."article
LEFT JOIN " . $tblpref ."categorie ON " . $tblpref ."categorie.id_cat = " . $tblpref ."article.cat
WHERE actif != 'non'
LIMIT $from, $row";

mysql_query("set names 'utf8'");
$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
$result1["rows"] = $result;

//count articles nb
$rs0 = mysql_query("select count(*) from " . $tblpref ."article WHERE actif != 'non'");
$row0 = mysql_fetch_row($rs0);
$result1["total"] = $row0[0];

//return json
echo json_encode($result1);

?>
