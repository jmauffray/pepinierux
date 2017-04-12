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
 * File Name: fckconfig.js
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
echo '<link rel="stylesheet" type="text/css" href="include/style.css">';
echo '<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico" >';
$quanti=isset($_POST['quanti'])?$_POST['quanti']:"";
$article=isset($_POST['article'])?$_POST['article']:"";
$lot=isset($_POST['lot'])?$_POST['lot']:"";
$num=isset($_POST['num'])?$_POST['num']:"";
$nom=isset($_POST['nom'])?$_POST['nom']:"";
?>
<SCRIPT language="JavaScript" type="text/javascript">
		function confirmDelete()
		{
		var agree=confirm('<?php echo "$lang_conf_effa"; ?>');
		if (agree)
		 return true ;
		else
		 return false ;
		}
		</script> 
		<?php
if($article=='0' || $quanti=='' )
    {
    echo "<p><center><h1>$lang_champ_oubli </p>";
    include('form_commande.php'); // On inclus le formulaire d'identification
    exit;
    }
echo '<table width="760" border="0" class="page" align="center"><td>';
echo "<center><table class='boiteaction'>
  <caption>
	<center>$lang_nv_bon</font><hr>
  </caption>";

//touver le dernier enregistrement pour le numero de bon
$sql = "SELECT MAX(num_bon) As Maxi FROM " . $tblpref ."bon_comm";
$result = mysql_query($sql) or die('Erreur');
$max = mysql_result2($result, 'Maxi');
//trouver le client correspodant au dernier bon
$sql = "SELECT client_num FROM " . $tblpref ."bon_comm WHERE num_bon = $max";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
    {
		$num = $data['client_num'];
		}
//on recupere le prix htva		
$sql2 = "SELECT prix_htva FROM " . $tblpref ."article WHERE num = $article";
$result = mysql_query($sql2) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
$prix_article = mysql_result2($result, 'prix_htva');
//on recupere le taux de tva
$sql3 = "SELECT taux_tva FROM " . $tblpref ."article WHERE num = $article";
$result = mysql_query($sql3) or die('Erreur SQL !<br>'.$sql3.'<br>'.mysql_error());
$taux_tva = mysql_result2($result, 'taux_tva');

$total_htva = $prix_article * $quanti ;
$mont_tva = $total_htva / 100 * $taux_tva ;
//inserer les donnees dans la table du conten des bons.
mysql_select_db($db) or die ("Could not select $db database");
$sql1 = "INSERT INTO " . $tblpref ."cont_bon(p_u_jour, quanti, article_num, bon_num, tot_art_htva, to_tva_art, num_lot) 
VALUES ('$prix_article', '$quanti', '$article', '$max', '$total_htva', '$mont_tva', '$lot')";
mysql_query($sql1) or die('Erreur SQL !<br>'.$sql1.'<br>'.mysql_error());
 ?>
 <th><? echo $lang_quantite ;?></th>
  <th><? echo $lang_unite ;?></th>
  <th><? echo $lang_article ;?></th>
  <th><? echo $lang_montant_htva ;?></th>
	<th><? echo "n° de lot"; ?>
  <th><? echo $lang_editer ;?></th>
  <th><? echo $lang_supprimer ;?></th>
<tr>
 <?php 
//on recherche tout les contenus du bon et on les detaille
$sql = "SELECT " . $tblpref ."cont_bon.num ,num_lot, uni, quanti, article, tot_art_htva FROM " . $tblpref ."cont_bon RIGHT JOIN " . $tblpref ."article on " . $tblpref ."cont_bon.article_num = " . $tblpref ."article.num WHERE  bon_num = $max";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
    {
		$quanti = $data['quanti'];
		$uni = $data['uni'];
		$article = $data['article'];
		$tot = $data['tot_art_htva'];
		$num_cont = $data['num'];//$lang_li_tot2
		$num_lot = $data['num_lot'];
		
		echo "<td class =' couleur_alternee ()'>$quanti
		<td class =' couleur_alternee (FALSE)'>$uni 
		<td class =' couleur_alternee (FALSE)'>$article 
		<td class =' couleur_alternee (FALSE)'>$tot €
		<td class =' couleur_alternee (FALSE)'><a href=voir_lot.php?num=$num_lot target='_blanck'>$num_lot</a>
		<td class =' couleur_alternee (FALSE)'><a href=edit_cont_bon.php?num_cont=$num_cont><img border=0 alt=editer src=image/edit.gif></a>&nbsp;
		<td class =' couleur_alternee (FALSE)'><a href=delete_cont_bon.php?num_cont=$num_cont&num_bon=$max onClick='return confirmDelete()'><img border=0 src= image/delete.jpg ></a>&nbsp;<tr>";
		 }
//on calcule la somme des contenus du bon
$sql = " SELECT SUM(tot_art_htva) FROM " . $tblpref ."cont_bon WHERE bon_num = $max";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
    {
		$total_bon = $data['SUM(tot_art_htva)'];
		//echo "$lang_som_tot2 <font size = 4>$total_bon</font> ";
		}
//on calcule la some de la tva des contenus du bon
$sql = " SELECT SUM(to_tva_art) FROM " . $tblpref ."cont_bon WHERE bon_num = $max";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
    {
		$total_tva = $data['SUM(to_tva_art)'];
		}?>
<tr><td class='totalmontant' colspan="3"><?php echo $lang_total_h_tva; ?></td>
<td class='totaltexte'><?php echo "$total_bon $devise"; ?>  </td>
<td class='totaltexte'><?php echo "$total_tva $devise $lang_tva"; ?></td><td colspan='2' class='totalmontant'></td> </tr>
<table class="boiteaction">
  <caption>
  <?php echo "$lang_bon_ajouter $lang_numero $num_bon"; ?> 
  </caption>
<?php 
 echo "<form name='form1' method='post' action='bon_suite.php'><td class='texte0'>$lang_quanti";
 echo "<td class='texte0'colspan='3'><input name='quanti' type='text' id='quanti' size='6'>";
 echo "<tr><td class='texte0'>$lang_article";
$rqSql = "SELECT num, article, prix_htva FROM " . $tblpref ."article WHERE actif != 'non' ORDER BY article, prix_htva";
$result = mysql_query( $rqSql )
             or die( "Execution requete impossible.");
$ld = "<SELECT NAME='article'>";
$ld .= "<OPTION VALUE=0>Choisissez</OPTION>";
while ( $row = mysql_fetch_array( $result)) {
    $num = $row["num"];
    $article = $row["article"];
		$prix = $row["prix_htva"];
    $ld .= "<OPTION VALUE='$num'>$article $prix</OPTION>";
}
$ld .= "</SELECT>";?>
<td class='texte0'>
<?php 
print $ld;
?>
		<?php $rqSql = "SELECT num, prod FROM " . $tblpref ."lot WHERE actif != 'non' ORDER BY num";
			$result = mysql_query( $rqSql )
             or die( "Execution requete impossible.");?>
<td class="texte0">Lot</td>
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
     <input name="nom" type="hidden" id="nom" value=<?php echo $nom ?>>
    <center><tr><td class="submit" colspan="4"><input type="submit" name="Submit" value=<?php echo $lang_ajou_bon ?>></tr>
    </form>
<form action="bon_fin.php" method="post" name="fin_bon">
<tr> 
<table class="boiteaction">
  <caption>
  <?php echo "$lang_bon_enregistrer "; ?> 
  </caption>
  <td class="submit" >
	<?php echo $lang_ajo_com_bo ?></tr><tr>
<td class="submit" colspan="2"><textarea name="coment" cols="45" rows="3"></textarea>
<input type="hidden" name="tot_ht" value=<?php echo $total_bon ?>>
<input type="hidden" name="tot_tva" value=<?php echo $total_tva ?>>
<input type="hidden" name="bon_num" value=<?php echo $max ?>>
<tr><td colspan="2" class="submit"><center><input type="submit" name="Submit" value=<?php echo $lang_ter_enr ?>>
</form></td><br>    
</table><br><hr><?php 
include("include/bas.php");
 ?>