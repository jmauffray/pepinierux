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
 * * * Version:  1.1.5
 * * * * Modified: 23/07/2005
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
include_once("include/headers.php");
include_once("include/autocomplete-headers.php");
include_once("include/finhead.php");
include_once("include/autocomplete.php");
include_once("include/configav.php");
include_once("bon_suite_util.php");
$quanti=isset($_POST['quanti'])?$_POST['quanti']:"";
$remise=isset($_POST['remise'])?$_POST['remise']:"";
$prix_remise=isset($_POST['prix_remise'])?$_POST['prix_remise']:"";
$volume_pot=isset($_POST['volume_pot'])?$_POST['volume_pot']:"";
$article=isset($_POST['article'])?$_POST['article']:"";
if( $article=='' )
  {
    $article=isset($_POST['chef_id'])?$_POST['chef_id']:"";
  }
$num=isset($_POST['num'])?$_POST['num']:"";
$lot1=isset($_POST['lot'])?$_POST['lot']:"";
$nom=isset($_POST['nom'])?$_POST['nom']:"";
$type=isset($_POST['type'])?$_POST['type']:"";

$prix_htva=getPrixHtvaColumn($type, $quanti);
$taux_tva_column=  getTauxTvaColumn($type);
?>
<body onload="document.forms[0].elements[0].focus()">
<SCRIPT language="JavaScript" type="text/javascript">
function confirmDelete(num)
{
    var agree=confirm('<?php echo "$lang_conf_effa"; ?>'+num);
    if (agree)
	return true ;
    else
	return false ;
}
</script>
<?php
if($article=='' || $quanti=='' )
  {
    $message= "<h1>$lang_champ_oubli </h1>";
    include('form_commande.php'); // On inclus le formulaire d'identification
    exit;
  }
?>
<table width="760" border="0" class="page" align="center">
  <tr><TD>
    <table class='boiteaction'>
      <?php
      $max = addArticleInBon(
              $quanti, $remise, $prix_remise, $volume_pot, $article, $prix_htva, $type, $lot1, $taux_tva_column);
      ?>
      <caption><?php echo"$lang_nv_bon"; ?> </caption>
      <tr>
	<th><?php echo $lang_quantite ;?></th>
	<th><?php echo $lang_article ;?></th>
	<th><?php echo $lang_remise ;?></th>
	<th><?php echo $lang_volume_pot ;?></th>
	<th><?php echo $lang_montant_htva ;?></th>
	<?php
	  if ($lot =='y') {?>
	<th><?php echo "$lang_lot"; ?></th>
	<?php } ?>
	<th><? echo $lang_editer ;?></th>
	<th><? echo $lang_supprimer ;?></th>

	<?php
	  //on recherche tout les contenus du bon et on les detaille
	  $sql = "SELECT " . $tblpref ."cont_bon.num ,num_lot, uni, quanti, article, tot_art_htva, remise, volume_pot FROM " . $tblpref ."cont_bon RIGHT JOIN " . $tblpref ."article on " . $tblpref ."cont_bon.article_num = " . $tblpref ."article.num WHERE  bon_num = $max";
	  $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

          $nombre = 0;
	  while($data = mysql_fetch_array($req))
	    {
	      $quanti = $data['quanti'];
	      $remise = $data['remise'];
	      $volume_pot = $data['volume_pot'];
	      $uni = $data['uni'];
	      $article = $data['article'];
	      $tot = $data['tot_art_htva'];
	      $num_cont = $data['num'];//$lang_li_tot2
	      $num_lot = $data['num_lot'];
	      $nombre = $nombre +1;
	      if($nombre & 1){
		$line="0";
	      }else{
		$line="1";
	      }
	?>
	<tr class="texte<?php echo"$line" ?>" onmouseover="this.className='highlight'" onmouseout="this.className='texte<?php echo"$line" ?>'">
	<td class ='highlight'><?php echo"$quanti";?>
	<td class ='highlight'><?php echo"$article";?>
	<td class ='highlight'><?php echo"$remise";?>
	<td class ='highlight'><?php echo"$volume_pot";?>
	<td class ='highlight'><?php echo"$tot $devise"; ?>
	<?php
	  if ($lot =='y') { ?>
  	<td class ='highlight'><a href=voir_lot.php?num=<?php echo"$num_lot";?> target='_blank'><?php echo"$num_lot";?></a>
	<?php } ?>
	<td class ='highlight'><a href="edit_cont_bon.php?num_cont=<?php echo"$num_cont";?>&type=<?php echo"$type";?>"><img border="0" alt="editer" src="image/edit.gif"></a>
	<td class ='highlight'><a href="delete_cont_bon.php?num_cont=<?php echo"$num_cont";?>&amp;num_bon=<?php echo"$max"; ?>" onClick='return confirmDelete(<?php echo"$num_cont"; ?>)'><img border="0" src="image/delete.jpg" alt="effacer" ></a>&nbsp;</tr>
	<?php }
	  //on calcule la somme des contenus du bon
	  $sql = " SELECT SUM(tot_art_htva) FROM " . $tblpref ."cont_bon WHERE bon_num = $max";
	  $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	  while($data = mysql_fetch_array($req))
	    {
	      $total_bon = $data['SUM(tot_art_htva)'];
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
	<td class='totaltexte'><?php echo "$total_tva $devise $lang_tva"; ?></td><td colspan='2' class='totalmontant'>
      </td></tr></table>
      <form name='formu2' method='post' action='bon_suite.php'>
	<table class="boiteaction">
	  <caption>
	    <?php echo "$lang_bon_ajouter $lang_numero $max"; ?>
	  </caption>
<tr><td class='texte0'><?php echo"$lang_article"; ?>
	    <?php
	      include_once("include/configav.php");
	      if ($use_categorie !='y') {
		$rqSql = "SELECT num, article, $prix_htva FROM " . $tblpref ."article WHERE actif != 'non' ORDER BY article, $prix_htva";
		$result = mysql_query( $rqSql )
		  or die( "Exécution requête impossible.");
		$ld = "<SELECT NAME='article'>";
		$ld .= "<OPTION VALUE=0>Choisissez</OPTION>";
		while ( $row = mysql_fetch_array( $result)) {
		  $num = $row["num"];
		  $article = $row["article"];
		  $prix = $row["$prix_htva"];
		  $ld .= "<OPTION VALUE='$num'>$article $prix</OPTION>";
		}
	    $ld .= "</SELECT>";?>
	    <td class='texte0'>
	      <?php

		print $ld;
		}else{
		   echo "<td class='texte0'>";
		   include("include/categorie_choix.php");
		 }
		if ($lot =='y') {

		  $rqSql = "SELECT num, prod FROM " . $tblpref ."lot WHERE actif != 'non' ORDER BY num";
		  $result = mysql_query( $rqSql )
              or die( "Exécution requête impossible.");?>
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
		<?php
		  }
		?>
	  <tr><td class='texte0'><?php echo "$lang_quanti"; ?>
	  <td class='texte0'colspan='3'><input name='quanti' type='text' id='quanti' size='6' value='1'>
	    <tr><td class='texte0'><?php echo "$lang_remise"; ?>
	    <td class='texte0'colspan='3'><input name='remise' type='text' id='remise' size='6'>
	    <tr><td class='texte0'><?php echo "$lang_prix_remise"; ?>
	    <td class='texte0'colspan='3'><input name='prix_remise' type='text' id='prix_remise' size='6'>
<tr><td class='texte0'><?php echo "$lang_volume_pot"; ?>
	    <td class='texte0'colspan='3'><input name='volume_pot' type='text' id='volume_pot' size='6'>

		<tr><td class="submit" colspan="4">
<input name="nom" type="hidden" id="nom" value="<?php echo $nom ?>">
<input type="hidden" name="type" value=<?php echo $type ?>>
<input type="submit" name="Submit" value=<?php echo $lang_ajou_bon ?>>
</td></tr>
	      </table></form>
	      <tr><td>

		<form action="bon_fin.php" method="post" name="fin_bon">

		  <center><table class="boiteaction">
		    <caption>
		      <?php echo "$lang_bon_enregistrer "; ?>
		    </caption>
		    <tr>
		      <td class="submit" >
			<?php echo $lang_ajo_com_bo ?><tr>
			<td class="submit" colspan="2"><textarea name="coment" cols="45" rows="3"></textarea>
			<input type="hidden" name="tot_ht" value=<?php echo $total_bon ?>>
			<input type="hidden" name="tot_tva" value=<?php echo $total_tva ?>>
			<input type="hidden" name="bon_num" value=<?php echo $max ?>>
			<input type="hidden" name="type" value=<?php echo $type ?>>
			<tr>
			  <td colspan="2" class="submit">
			    <center><input type="submit" name="Submit" value="<?php echo "$lang_ter_enr"; ?>">
			  </center>

			  </table></center></form></td><tr><td>
			  <?php
			    include("include/bas.php");
			  ?>
			</td></tr></table>
