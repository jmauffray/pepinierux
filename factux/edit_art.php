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
 * File Name: edit_art.php
 * 	Permet de modifier certains parametres des articles.
 * 
 * * * Version:  1.1.5
 * * * * Modified: 23/07/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
include_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/config/var.php");

include_once("include/language/$lang.php");
include_once("include/headers.php");
include_once("include/finhead.php");

$article=isset($_GET['article'])?$_GET['article']:"";
$sql = "SELECT * FROM " . $tblpref ."article  left join " . $tblpref ."categorie on " . $tblpref ."article.cat = " . $tblpref ."categorie.id_cat
																   WHERE num=$article";

$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
  {
    $article = $data['article'];
    $variete = $data['variete'];
    $contenance = $data['contenance'];
    $phyto = $data['phyto'];
    $num =$data['num'];
    $prix = $data['prix_htva'];
    $prix_gros = $data['prix_htva_gros'];
    $tva = $data['taux_tva'];
    $prix_part = $data['prix_htva_part'];
    $prix_part_ttc = $data['prix_ttc_part'];
    $tva_part = $data['taux_tva_part'];
    $uni = $data['uni'];
    $stock = $data['stock'];
    $cate = $data['categorie'];
    $taille = $data['taille'];
    $conditionnement = $data['conditionnement'];
    $cat_id = $data['id_cat'];
  }
?>			
<table width="760" border="0" class="page" align="center">
  <tr>
    <td class="page" align="center">
      <?php
	include_once("include/head.php");
      ?>
      <tr><td><?php echo"<h2>$lang_modi_pri $article : $num</h2>"; ?></tr><tr><td>

      <center><form action=<?php echo"article_update.php#$num"; ?> method="post" name="article" id="article">
	<table>
	  <tr>
	    <?php
	      if($use_stock=='y'){?>
	    <th><?php echo "Plante"; ?></th>
	    <th><?php echo "$lang_variete"; ?></th>
	    <th><?php echo "Cont."; ?></th>
	    <th><?php echo "phyto"; ?></th>
	    <th><?php echo "$lang_taille"; ?></th>
	    <th><?php echo "$lang_conditionnement"; ?></th>
	    <th><?php echo "$lang_prixunitaire " ?></th>
	    <th><?php echo "Tarif HT Gros" ?></th>
	    <th><?php echo "Part:Tarif TTC" ?></th>
	    <th><?php echo "$lang_stock"; ?></th>
	    <?php } ?>
	    <?php
	      include_once("include/configav.php");
	    if ($use_categorie =='y') { ?>
	    <th><?php echo "$lang_categorie" ?></th>
	    <?php } ?>
	    <th><?php echo "%TVA"; ?></th>
	    <th><?php echo "Part : %TVA"; ?></th>
	  </tr>
	  <tr>
	    <td><input size=8 name="article" type="text" value ="<?php echo"$article" ?>"></td>
	    <td><input size=8 name="variete" type="text" value ="<?php echo"$variete" ?>"></td>
	    <td><input size=8 name="contenance" type="text" value ="<?php echo"$contenance" ?>"></td>
	    <td><input size=2 name="phyto" type="text" value ="<?php echo"$phyto" ?>"></td>
	    <td><input size=8 name="taille" type="text" value ="<?php echo"$taille" ?>"></td>
	    <td>
	      <SELECT NAME='conditionnement'>
		<OPTION VALUE='<?php echo"$conditionnement" ?>'><?php echo"$conditionnement" ?></OPTION>
		<OPTION VALUE='conteneur'>conteneur</OPTION>
		<OPTION VALUE='motte'>motte</OPTION>
		<OPTION VALUE='motte en pot'>motte en pot</OPTION>
		<OPTION VALUE='racines nues'>racines nues</OPTION>
		<OPTION VALUE='godet'>godet</OPTION>
		<OPTION VALUE=''></OPTION>
	      </SELECT>
	    </td>
	    <td><input size=8 name="prix" type="text"  value ="<?php echo"$prix" ?> <?php echo "$devise" ?>"></td>
	    <td><input size=8 name="prix_gros" type="text"  value ="<?php echo"$prix_gros" ?> <?php echo "$devise" ?>"></td>
	      <td><input size=8 name="prix_part_ttc" type="text"  value ="<?php echo"$prix_part_ttc" ?> <?php echo "$devise" ?>"></td>
	    <?php
	      if($use_stock=='y'){?>
	    <td><input size=8 name="stock" type="text" value ="<?php echo"$stock" ?>"></td>
	    <?php  if ($use_categorie =='y') { ?>
	    <td>
	      <?php 
		$rqSql = "SELECT id_cat, categorie FROM " . $tblpref ."categorie WHERE 1";
	      $result = mysql_query( $rqSql ) or die( "ExÃÂÃÂÃÂÃ¢ÂÂÃÂÃ¢ÂÂ ÃÂ¢Ã¢ÂÂ¬Ã¢ÂÂ¢ÃÂÃÂÃÂ¢Ã¢ÂÂ¬ÃÂ¡ÃÂÃ¢ÂÂÃÂÃÂ©cution requÃÂÃÂÃÂÃ¢ÂÂÃÂÃ¢ÂÂ ÃÂ¢Ã¢ÂÂ¬Ã¢ÂÂ¢ÃÂÃÂÃÂ¢Ã¢ÂÂ¬ÃÂ¡ÃÂÃ¢ÂÂÃÂÃÂªte impossible."); ?> 
	      <SELECT NAME='categorie'>
		<OPTION VALUE='<?php echo"$cat_id" ?>'><?php echo $cate; ?></OPTION>
		<?php
		  while ( $row = mysql_fetch_array( $result)) {
		    $num_cat = $row["id_cat"];
		    $categorie = $row["categorie"];
		?>
		<OPTION VALUE='<?php echo "$num_cat" ; ?>'><?php echo "$categorie"; ?></OPTION>
		<?
		  }
		?>
	      </SELECT></td>
	      <td><input size=8 name="tva" type="text" value ="<?php echo"$tva" ?>"></td>
	      <td><input size=8 name="tva_part" type="text" value ="<?php echo"$tva_part" ?>"></td>
	      <?php } ?>

	      <?php } ?>
	      <tr>
		<td colspan="4" class="submit">
		  <input name="num" type="hidden" value= <?php echo "$num" ?>  />
		  <input type="submit" name="Submit" value="Modifier">
		    <input type="submit" name="Submit" value="Creer">
		      <?php
			if($use_stock=='y'){?>
		      <td colspan="2" class="submit">
			<?php } ?>
			<input name="reset" type="reset" id="reset" value="effacer">
			</table>
		      </form>
		    </center>
		    <?php
		      echo "<tr><td>";
		      include_once("include/bas.php");
		    ?>
		  </td>
		</tr>
	      </table>
