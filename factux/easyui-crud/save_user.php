<?php

$article = $_REQUEST['article'];
$variete = $_REQUEST['variete'];
$taille = $_REQUEST['taille'];
$conditionnement = $_REQUEST['conditionnement'];
$contenance = $_REQUEST['contenance'];
$prix_ttc_part = $_REQUEST['prix_ttc_part'];
$taux_tva_part = $_REQUEST['taux_tva_part'];
$prix_htva = $_REQUEST['prix_htva'];
$prix_htva_gros = $_REQUEST['prix_htva_gros'];
$taux_tva = $_REQUEST['taux_tva'];
$stock = $_REQUEST['stock'];
$cat = $_REQUEST['cat'];
$phyto = $_REQUEST['phyto'];

include '../include/config/common.php';


$sql = "insert into " . $tblpref ."article(article, variete, taille, conditionnement, contenance, prix_ttc_part, prix_htva_part, taux_tva_part, prix_htva, prix_htva_gros, taux_tva, uni, stock, cat, phyto)
values '$article', '$variete', '$taille', '$conditionnement', '$contenance', '$prix_ttc_part', '$prix_htva_part', '$taux_tva_part', '$prix_htva', '$prix_htva_gros', '$taux_tva', 'pcs', '$stock', '$cat', '$phyto')";

mysql_query("set names 'utf8'");
mysql_query($sql);

echo json_encode(array(
	'num' => mysql_insert_id(),
	'article' => $article,
	'variete' => $variete,
	'taille' => $taille,
	'conditionnement' => $conditionnement,
	'contenance' => $contenance,
	'prix_ttc_part' => $prix_ttc_part,
	'prix_htva_part' => $prix_htva_part,
	'taux_tva_part' => $taux_tva_part,
	'prix_htva' => $prix_htva,
	'prix_htva_gros' => $prix_htva_gros,
	'taux_tva' => $taux_tva,
	'stock' => $stock,
	'cat' => $cat,
	'phyto' => $phyto
));

?>
