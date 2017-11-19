<?php
include_once("include/config/common.php");
include_once("include/utils.php");

function getPrixHtvaColumn($type, $quanti)
{
  $prix_htva='prix_htva';
  if( $type=='particulier' )
  {
    $prix_htva='prix_htva_part';
  }

  return $prix_htva;
}


function addArticleInBon(
$quanti,
$remise,
$prix_remise,
$volume_pot,
$article,
$prix_htva,
$type,
$lot1)
{
  global $tblpref;
  //touver le dernier enregistrement pour le numero de bon
  $sql = "SELECT MAX(num_bon) As Maxi FROM " . $tblpref ."bon_comm";
  $result = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	$max = mysql_result2($result, 'Maxi');
  //trouver le client correspodant au dernier bon
  $sql = "SELECT client_num FROM " . $tblpref ."bon_comm WHERE num_bon = $max";
  $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
  while($data = mysql_fetch_array($req))
  {
    $num = $data['client_num'];
  }
  //on recupere le prix htva
  $sql2 = "SELECT $prix_htva FROM " . $tblpref ."article WHERE num = $article";
  $result = mysql_query($sql2) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
	$prix_article = mysql_result2($result, $prix_htva);

  //on recupere le taux de tva
  $sql3 = "SELECT taux_tva FROM " . $tblpref ."article WHERE num = $article";
  $result = mysql_query($sql3) or die('Erreur SQL !<br>'.$sql3.'<br>'.mysql_error());
	$taux_tva = mysql_result2($result, 'taux_tva');

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
    $prix_article += ($volume_pot * 0.15);
  }

  $montant_u_htva = $prix_article * (100-$remise)/100;
  $total_htva = $quanti * $montant_u_htva;
  $mont_tva = $total_htva / 100 * $taux_tva ;

  //inserer les données dans la table du conten des bons.
  $sql1 = "INSERT INTO " . $tblpref ."cont_bon(p_u_jour, p_u_jour_net, quanti, remise, volume_pot, conditionnement, article_num, bon_num, tot_art_htva, to_tva_art, num_lot)
  VALUES ('$prix_article', '$montant_u_htva', '$quanti', '$remise', '$volume_pot', '$conditionnement', '$article', '$max', '$total_htva', '$mont_tva', '$lot1')";


  mysql_query($sql1) or die('Erreur SQL !<br>'.$sql1.'<br>'.mysql_error());
  //ici on decremnte le stock
  $sql12 = "UPDATE `" . $tblpref ."article` SET `stock` = (stock - $quanti) WHERE `num` = '$article'";
  mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());
  $sql12 = "UPDATE `" . $tblpref ."article` SET `stock_disponible` = (stock_disponible - $quanti) WHERE `num` = '$article'";
  mysql_query($sql12) or die('Erreur SQL12 !<br>'.$sql12.'<br>'.mysql_error());

  return $max;
}

?>
