<?php

$firstname = $_REQUEST['article'];
$lastname = $_REQUEST['variant'];
$phone = $_REQUEST['taille'];
//$email = $_REQUEST['email'];

include '../include/config/common.php';
//include 'conn.php';
mysql_query("set names 'utf8'");
$sql = "insert into " . $tblpref ."article(article,variete,taille) values('$firstname','$lastname','$phone')";

@mysql_query($sql);
echo json_encode(array(
	'num' => mysql_insert_id(),
	'article' => $firstname,
	'variete' => $lastname,
	'taille' => $phone
	//'email' => $email
));

?>
