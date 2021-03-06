<?php 
/*
 * Factux le facturier libre
 * Copyright (C) 2003-2004 Guy Hendrickx
 * 
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 * 
 * For further information visit:
 * 		http://factux.sourceforge.net
 * 
 * File Name: voir_lot.php
 * 	montre les lots
 * 
 * * Version:  1.1.4
 * * Modified: 25/04/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
require_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/config/var.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
?>
<head>
<title><?php echo "$lang_factux" ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/style.css">
<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico" >
</head>

<body>
<table width="500" border="0" class="page" align="center">
<tr>
<td class="page" align="center">
<?php
include_once("include/head.php");
?>
</td>
</tr>
<tr>
<td  class="page" align="center">
<?php 
if ($user_com == 'n') { 
echo"<h1>$lang_commande_droit";
exit;  
}
$num_get=isset($_GET['num'])?$_GET['num']:"";
$sql = "SELECT ingr, fourn, fourn_lot 
		 FROM " . $tblpref ."cont_lot 
		 WHERE num_lot= $num_get";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
?>
     
        <center>
        <center><table class="boiteaction">
  <caption><?php echo "Contenu du lot $num_get"; ?></caption>
          <tr> 
            <th><?php echo Ingredient; ?></th>
            <th><?php echo "Fournisseur"; ?></th>
            <th><?php echo "fourn_lot"; ?></th>
            </tr>
          <?php
while($data = mysql_fetch_array($req))
{
  $ingr = $data['ingr'];
  $fourn = $data['fourn'];
  $fourn_lot = $data['fourn_lot'];
  ?>
          <tr> 
            <td  class='<?php echo couleur_alternee (); ?>'><?php echo $ingr; ?></td>
            <td class='<?php echo couleur_alternee (FALSE); ?>'><?php echo "$fourn"; ?></td>
            <td class='<?php echo couleur_alternee (FALSE); ?>'><?php echo $fourn_lot; ?></td>
             

</td>
</tr>
<?php } ?> 
</table>
   </td></tr>
</table>
<?php
include("help.php");
include_once("include/bas.php");
?>
</body>
</html>
