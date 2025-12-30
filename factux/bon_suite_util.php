<?php
include_once("include/config/common.php");
include_once("include/utils.php");

function getTauxTvaColumn($type)
{
  $taux_tva='taux_tva';
  if( $type=='particulier' )
  {
    $taux_tva='taux_tva_part';
  }

  return $taux_tva;
}

function getPrixHtvaColumn($type)
{
  $prix_htva='prix_htva';
  if( $type=='particulier' )
  {
    $prix_htva='prix_htva_part';
  }

  return $prix_htva;
}


function getLastNumBon()
{
  global $tblpref;
  
  //touver le dernier enregistrement pour le numero de bon
  $sql = "SELECT MAX(num_bon) As Maxi FROM " . $tblpref ."bon_comm";
  $result = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
  $max = mysql_result2($result, 'Maxi');
  
  return $max;
}
  
  
function addArticleInBon(
        $numBon,
$quanti,
$remise,
$prix_remise,
$volume_pot,
$article,
$type,
$lot1)
{
  global $tblpref;
  
  //get tva column
  $prix_htva_column = getPrixHtvaColumn($type);
  $taux_tva_column = getTauxTvaColumn($type);
  
  //on recupere le prix htva
  $sql2 = "SELECT $prix_htva_column FROM " . $tblpref ."article WHERE num = $article";
  $result = mysql_query($sql2) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
	$prix_article = mysql_result2($result, $prix_htva_column);

  //on recupere le taux de tva
  $sql3 = "SELECT $taux_tva_column FROM " . $tblpref ."article WHERE num = $article";
  $result = mysql_query($sql3) or die('Erreur SQL !<br>'.$sql3.'<br>'.mysql_error());
	$taux_tva = mysql_result2($result, $taux_tva_column);

  //on recupere le conditionnement
  $sql4 = "SELECT conditionnement FROM " . $tblpref ."article WHERE num = $article";
  $result = mysql_query($sql4) or die('Erreur SQL !<br>'.$sql4.'<br>'.mysql_error());
	$conditionnement = mysql_result2($result, 'conditionnement');

  ////conditionnement
  if($volume_pot > 0)
  {
    $conditionnement = "motte en pot";
  }
  
  //premiere utilisation de la remise, on la calcule si nécessaire
  if(!is_string($prix_remise))
  {
      //fix issue to compare 0 to '' below
      $prix_remise = strval($prix_remise);
  }
  if( ($remise == 0) && ($prix_remise != '') ) {
    $thePrix = 0;
    if( $type == 'particulier' )
    {
      $thePrix = $prix_article * (1 + $taux_tva / 100);
    }
    else
    {
      $thePrix = $prix_article;
    }
    
    if( $thePrix !=  0 )
    {
      $remise = (1 - $prix_remise / $thePrix ) * 100;
    }
  }
  
  //prise en compte du volume
  if( $prix_remise == '' )
  {
    $prix_article += ($volume_pot * $price_per_liter);
  }

  $montant_u_htva = $prix_article * (100-$remise)/100;
  $total_htva = $quanti * $montant_u_htva;
  $mont_tva = $total_htva / 100 * $taux_tva ;

  //inserer les données dans la table du conten des bons.
  $sql1 = "INSERT INTO " . $tblpref ."cont_bon(p_u_jour, p_u_jour_net, quanti, remise, volume_pot, conditionnement, article_num, bon_num, tot_art_htva, to_tva_art, num_lot)
  VALUES ('$prix_article', '$montant_u_htva', '$quanti', '$remise', '$volume_pot', '$conditionnement', '$article', '$numBon', '$total_htva', '$mont_tva', '$lot1')";
  mysql_query($sql1) or die('Erreur SQL !<br>'.$sql1.'<br>'.mysql_error());
  
  updateArticleStock($quanti, $article);
}

function updateArticleStock(
$quanti,
$article)
{
  global $tblpref;
  
  //ici on decremnte le stock
  $sql12 = "UPDATE `" . $tblpref ."article` SET `stock` = (stock - $quanti), `date_modifie`=NOW() WHERE `num` = '$article'";
  mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());
  $sql12 = "UPDATE `" . $tblpref ."article` SET `stock_disponible` = (stock_disponible - $quanti), `date_modifie`=NOW() WHERE `num` = '$article'";
  mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());
}


function restoreArticleStock(
$quanti,
$article)
{
  global $tblpref;
  
  //ici on incremente le stock
  $sql12 = "UPDATE `" . $tblpref ."article` SET `stock` = (stock + $quanti), `date_modifie`=NOW() WHERE `num` = '$article'";
  mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());
  $sql12 = "UPDATE `" . $tblpref ."article` SET `stock_disponible` = (stock_disponible + $quanti), `date_modifie`=NOW() WHERE `num` = '$article'";
  mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());
}


function restoreStockDeletedBon(
$numBon) {
    global $tblpref;

    $sql = "SELECT quanti, article_num
        FROM " . $tblpref . "cont_bon 
		WHERE  bon_num = $numBon";
    $req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());

    while ($data = mysql_fetch_array($req)) {
        $quanti = $data['quanti'];
        $articleNum = $data['article_num'];

        restoreArticleStock($quanti, $articleNum);
    }
}


function restoreStockDeletedContBon(
$numContBon) {
    global $tblpref;

    $sql = "SELECT quanti, article_num
        FROM " . $tblpref . "cont_bon 
		WHERE  num = $numContBon";
    $req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());

    while ($data = mysql_fetch_array($req)) {
        $quanti = $data['quanti'];
        $articleNum = $data['article_num'];

        restoreArticleStock($quanti, $articleNum);
    }
}

?>
