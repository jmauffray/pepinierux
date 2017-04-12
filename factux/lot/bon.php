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
 * File Name: bon.php
 * 	Editor configuration settings.
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
?><html>
<head>
<title><?php echo "$lang_factux" ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/style.css">
<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico" >
</head>

<body>
<table width="760" border="0" class="page" align="center">
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
$client=isset($_POST['client'])?$_POST['client']:"";
$date=isset($_POST['date'])?$_POST['date']:"";

list($jour, $mois,$annee) = preg_split('/\//', $date, 3);

include_once("include/language/$lang.php"); 
if($client=='0')
    {
    echo $lang_choix_client;
    include('form_commande.php');
    exit;
    }

$sql_nom = "SELECT  nom, nom2 FROM " . $tblpref ."client WHERE num_client = $client";
$req = mysql_query($sql_nom) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
    {
		$nom = $data['nom'];
		$nom2 = $data['nom2'];
		$phrase = "$lang_bon_cree";
		echo "$phrase $nom $nom2 $lang_bon_cree2<br>";
		}
mysql_select_db($db) or die ("Could not select $db database");
$sql1 = "INSERT INTO " . $tblpref ."bon_comm(client_num, date) VALUES ('$client', '$annee-$mois-$jour')";
mysql_query($sql1) or die('Erreur SQL !<br>'.$sql1.'<br>'.mysql_error());
?>
<body>
<br><center>
<form name='form1' method='post' action='bon_suite.php'>
    <table class="boiteaction">
      <caption><? echo $lang_donne_bon ; ?></caption>
      <tr>
        <td class="texte0"><?php echo $lang_quanti; ?> </td>
        <td class="texte0" colspan="3"><input name='quanti' type='text' id='quanti' size='6'></td>
				</tr>
				<tr>
			   <td class="texte0"><?php echo "$lang_article"; 
			$rqSql = "SELECT uni, num, article, prix_htva FROM " . $tblpref ."article WHERE actif != 'non' ORDER BY article, prix_htva";
			$result = mysql_query( $rqSql )
             or die( "Exécution requête impossible.");?>
        <td class="texte0"><SELECT NAME='article'>
            <OPTION VALUE=0><?php echo $lang_choisissez; ?></OPTION>
            <?php
						while ( $row = mysql_fetch_array( $result)) {
    							$num = $row["num"];
    							$article = $row["article"];
									$prix = $row["prix_htva"];
									$uni = $row["uni"];
    ?>
            <OPTION VALUE='<?php echo $num; ?>'><?php echo "$article $prix $devise"; ?></OPTION>
            <?php
}
?>
          </SELECT></td>
					<td class="texte0">Lot </td>
		<?php $rqSql = "SELECT num, prod FROM " . $tblpref ."lot WHERE actif != 'non' ORDER BY num";
			$result = mysql_query( $rqSql )
             or die( "Exécution requête impossible.");?>
					<td class="texte0"><SELECT NAME='lot'>
					<OPTION VALUE=0><?php echo $lang_choisissez; ?></OPTION>
            <?php
						while ( $row = mysql_fetch_array( $result)) {
    							$num = $row["num"];
    							$prod = $row["prod"];
		    ?>
            <OPTION VALUE='<?php echo $num; ?>'><?php echo "$num $prod "; ?></OPTION>
						
					<?php 
}
 ?> </SELECT></td>
      </tr>
      <tr>
        	 		<td class="submit" colspan="4"><input type="submit" name="Submit" value='<?php echo "$lang_valid "; ?>'></td>
      </tr>
    </table>
    <input name="nom" type="hidden" id="nom" value="<?php echo $nom ?>">
  </form>
</body>
</html>
<?php 
include("include/bas.php");
 ?>
</td></tr>
</table>
<?php
include("help.php");
include_once("include/bas.php");
?>
</body>
</html>
