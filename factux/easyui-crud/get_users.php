<?php

include '../include/config/common.php';

mysql_query("set names 'utf8'");

	$sql = "SELECT * FROM " . $tblpref ."article
	LEFT JOIN " . $tblpref ."categorie ON " . $tblpref ."categorie.id_cat = " . $tblpref ."article.cat
	WHERE actif != 'non' LIMIT 15";
	
$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}

echo json_encode($result);
?>
