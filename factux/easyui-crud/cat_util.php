<?php

function getCatId($categorie) {

  include '../include/config/common.php';

  mysql_query("set names 'utf8'");

  $sql = "SELECT * FROM `" . $tblpref ."categorie` WHERE `categorie`
  LIKE CONVERT( _utf8 '" . $categorie . "'
  USING latin1 )
  COLLATE latin1_general_ci";

  $result = @mysql_query($sql);
  if (!$result) {
    error_log("result - ".$result  . mysql_error());
    die('Requête invalide : ' . mysql_error());
  }
  $row0 = mysql_fetch_row($result);

  return $row0[0];
}

?>
