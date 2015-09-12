<?php
require_once("include/verif.php");
include_once("include/config/common.php");
//0.6Mo pour 1000 articles (3300 en base)
//$sql = "SELECT * FROM " . $tblpref ."article  LIMIT 0, 200";
$sql = "SELECT num, article, variete, taille, prix_ttc_part, prix_htva_part, categorie, stock FROM " . $tblpref ."article, " . $tblpref ."categorie WHERE actif != 'non' AND " .$tblpref ."article.cat = " .$tblpref ."categorie.id_cat";

mysql_query("set names 'utf8'");
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
$rows = array();

while($data = mysql_fetch_array($req, MYSQL_ASSOC))
  {
    $rows[] = $data;
  }

$s=json_encode($rows);
echo $s;

/*
  echo count($rows);
  $var=json_decode($s);
  var_dump($var);
  echo $var[0];

  foreach($var[0] as $item) { //foreach element in $arr
  echo "->" . $item[0]; //etc
  }

  echo "<br/>";

  switch (json_last_error()) {
  case JSON_ERROR_NONE:
  //          echo ' - Aucune erreur';
  break;
  case JSON_ERROR_DEPTH:
  echo ' - Profondeur maximale atteinte';
  break;
  case JSON_ERROR_STATE_MISMATCH:
  echo ' - Inadéquation des modes ou underflow';
  break;
  case JSON_ERROR_CTRL_CHAR:
  echo ' - Erreur lors du contrôle des caractères';
  break;
  case JSON_ERROR_SYNTAX:
  echo ' - Erreur de syntaxe ; JSON malformé';
  break;
  case JSON_ERROR_UTF8:
  echo ' - Caractères UTF-8 malformés, probablement une erreur d\'encodage';
  break;
  default:
  echo ' - Erreur inconnue';
  break;
  }
*/
?>
