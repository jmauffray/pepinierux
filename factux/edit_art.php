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

	  <form action=<?php echo"article_update.php"; ?> method="post" name="article" id="article">
	    <fieldset>
	      <legend>Plante</legend>
	      <span><label><?php echo "Plante "; ?></label><input size=18 name="article" type="text" value ="<?php echo"$article" ?>"></span><br/>
	      <span><label><?php echo "$lang_variete "; ?></label><input size=24 name="variete" type="text" value ="<?php echo"$variete" ?>"></span><br/>
	    </fieldset>
	    <fieldset>
	      <legend>Caracteristiques</legend>
	      <span><label><?php echo "Cont. "; ?></label><input size=4 name="contenance" type="text" value ="<?php echo"$contenance" ?>"></span><br/>
	      <span><label><?php echo "phyto "; ?></label><input size=2 name="phyto" type="text" value ="<?php echo"$phyto" ?>"></span><br/>
	      <span><label><?php echo "$lang_taille "; ?></label><input size=6 name="taille" type="text" value ="<?php echo"$taille" ?>"></span><br/>
              <span><label><?php echo "$lang_conditionnement "; ?></label>
		<SELECT NAME='conditionnement'>
		  <OPTION VALUE='<?php echo"$conditionnement" ?>'><?php echo"$conditionnement" ?></OPTION>
		  <OPTION VALUE='conteneur'>conteneur</OPTION>
		  <OPTION VALUE='motte'>motte</OPTION>
		  <OPTION VALUE='motte en pot'>motte en pot</OPTION>
		  <OPTION VALUE='MG d48'>MG d48</OPTION>
		  <OPTION VALUE='MG d62'>MG d62</OPTION>
		  <OPTION VALUE='racines nues'>racines nues</OPTION>
		  <OPTION VALUE='godet'>godet</OPTION>
                  <OPTION VALUE='godet x6'>godet x6</OPTION>
                  <OPTION VALUE='godet x10'>godet x10</OPTION>
		  <OPTION VALUE=''></OPTION>
	 	</SELECT>
	      </span><br/>
	    </fieldset>

	    <fieldset>
	      <legend>Tarif</legend>
	      <span><label><?php echo "$lang_prixunitaire " ?></label><input size=5 name="prix" type="text"  value ="<?php echo"$prix" ?> <?php echo "$devise" ?>"></span><br/>
	      <span><label><?php echo "Tarif HT Gros " ?></label><input size=5 name="prix_gros" type="text"  value ="<?php echo"$prix_gros" ?> <?php echo "$devise" ?>"></span><br/>
	      <span><label><?php echo "%TVA "?></label><input size=3 name="tva" type="text" value ="<?php echo"$tva" ?>"></span><br/>
        <br/>
	      <span><label><?php echo "Part:Tarif TTC " ?></label><input size=5 name="prix_part_ttc" type="text"  value ="<?php echo"$prix_part_ttc" ?> <?php echo "$devise" ?>"></span><br/>
	      <span><label><?php echo "Part : %TVA "?></label><input size=3 name="tva_part" type="text" value ="<?php echo"$tva_part" ?>"></span><br/>
	    </fieldset>

	    <fieldset>
	      <legend>Stock</legend>
	      <span><label><?php echo "$lang_stock "; ?></label><input size=8 name="stock" type="text" value ="<?php echo"$stock" ?>"></span><br/>
	      <span><label><?php echo "Origine "; ?></label>
		<?php
		   $rqSql = "SELECT id_cat, categorie FROM " . $tblpref ."categorie WHERE 1 ORDER BY id_cat DESC";
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
		</SELECT>
	      </span><br/>
	    </fieldset>
</table>

<input name="num" type="hidden" value= <?php echo "$num" ?>  />
<input type="submit" name="Submit" value="Modifier">
<input type="submit" name="Submit" value="Creer">
<?php
   if($use_stock=='y'){?>
<td colspan="2" class="submit">
  <?php } ?>
  <input name="reset" type="reset" id="reset" value="Effacer">
</table>
</form>
<?php
   echo "<tr><td>";
   include_once("include/bas.php");
   ?>
