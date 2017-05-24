<?php

include 'cat_util.php';

$id = intval($_REQUEST['num']);
$article = $_REQUEST['article'];
$variete = $_REQUEST['variete'];
$taille = $_REQUEST['taille'];
$conditionnement = $_REQUEST['conditionnement'];
$contenance = $_REQUEST['contenance'];
$prix_ttc_part = $_REQUEST['prix_ttc_part'];
$taux_tva_part = $_REQUEST['taux_tva_part'];
$prix_htva = $_REQUEST['prix_htva'];
$taux_tva = $_REQUEST['taux_tva'];
$stock = $_REQUEST['stock'];
$cat = getCatId($_REQUEST['categorie']);
$phyto = $_REQUEST['phyto'];

//compute
$prix_htva_part = ($prix_ttc_part * 100 / (100 + $taux_tva_part));

include '../include/config/common.php';

mysql_query("set names 'utf8'");
//num 	article 	variete 	taille 	conditionnement 	contenance 	prix_ttc_part 	prix_htva_part 	taux_tva_part 	prix_htva 	 	taux_tva 	commentaire 	uni 	actif 	stock 	stomin 	stomax 	cat 	phyto

$sql = "update " . $tblpref ."article set
article='$article',
variete='$variete',
taille='$taille',
conditionnement='$conditionnement',
contenance='$contenance',
prix_ttc_part='$prix_ttc_part',
prix_htva_part='$prix_htva_part',
taux_tva_part='$taux_tva_part',
prix_htva='$prix_htva',
taux_tva='$taux_tva',
stock='$stock',
cat='$cat',
phyto='$phyto'
where num=$id";
$result = @mysql_query($sql);
if (!$result) {
	error_log("result - ".$result  . mysql_error());
    die('Requête invalide : ' . mysql_error());
}
//num 	article 	variete 	taille 	conditionnement 	contenance 	prix_ttc_part 	prix_htva_part 	taux_tva_part 	prix_htva 	 	taux_tva 	commentaire 	uni 	actif 	stock 	stomin 	stomax 	cat 	phyto

echo json_encode(array(
	'num' => $id,
'article' => $article,
'variete' => $variete,
'taille' => $taille,
'conditionnement' => $conditionnement,
'contenance' => $contenance,
'prix_ttc_part' => $prix_ttc_part,
'prix_htva_part' => $prix_htva_part,
'tva_part' => $tva_part,
'prix_htva' => $prix_htva,
'tva' => $tva,
'stock' => $stock,
'cat' => $cat,
'phyto' => $phyto
));
?>
