<?php

$id = intval($_REQUEST['num']);
$firstname = $_REQUEST['article'];
$lastname = $_REQUEST['variete'];
$phone = $_REQUEST['taille'];
//$email = $_REQUEST['email'];

include '../include/config/common.php';

mysql_query("set names 'utf8'");

$sql = "update " . $tblpref ."article set article='$firstname',variete='$lastname',taille='$phone' where num=$id";
@mysql_query($sql);

//num 	article 	variete 	taille 	conditionnement 	contenance 	prix_ttc_part 	prix_htva_part 	taux_tva_part 	prix_htva 	prix_htva_gros 	taux_tva 	commentaire 	uni 	actif 	stock 	stomin 	stomax 	cat 	phyto

echo json_encode(array(
	'num' => $id,
	'article' => $firstname,
	'variete' => $lastname,
	'taille' => $phone
	//'email' => $email
));
?>
