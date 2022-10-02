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
   $prix_achat = $data['prix_achat'];
   $stock_disponible = $data['stock_disponible'];
   $localisation = $data['localisation'];
   $groupe_varietal = $data['groupe_varietal'];
   $description = $data['description'];
   $commentaire = $data['commentaire'];
   
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
	      <span><label><?php echo "Description "; ?></label><input size=45 name="description" type="text" value ="<?php echo"$description" ?>"></span><br/>
	    <span><label>Groupe varietal</label>
		<SELECT NAME='groupe_varietal'>
		  <OPTION VALUE='<?php echo"$groupe_varietal" ?>'><?php echo"$groupe_varietal" ?></OPTION>
		  <OPTION VALUE='Arbres'>Arbres</OPTION>
		  <OPTION VALUE='Arbres fruitiers'>Arbres fruitiers</OPTION>
		  <OPTION VALUE='Arbustes'>Arbustes</OPTION>
		  <OPTION VALUE='Aromatique'>Aromatique</OPTION>
		  <OPTION VALUE='Bambou'>Bambou</OPTION>
		  <OPTION VALUE='Buis et topiaire'>Buis et topiaire</OPTION>
		  <OPTION VALUE='Camélia'>Camélia</OPTION>
		  <OPTION VALUE='Conifères'>Conifères</OPTION>
		  <OPTION VALUE='Cornus de greffe'>Cornus de greffe</OPTION>
		  <OPTION VALUE='Erables du japon'>Erables du japon</OPTION>
		  <OPTION VALUE='Graminées'>Graminées</OPTION>
		  <OPTION VALUE='Grimpantes'>Grimpantes</OPTION>
		  <OPTION VALUE='Lilas'>Lilas</OPTION>
		  <OPTION VALUE='Petits fruits'>Petits fruits</OPTION>
		  <OPTION VALUE='Pittosporum'>Pittosporum</OPTION>
		  <OPTION VALUE='Plantes de terre de bruyère'>Plantes de terre de bruyère</OPTION>
		  <OPTION VALUE='Plants maraicher'>Plants maraicher</OPTION>
		  <OPTION VALUE='Produits complémentaires'>Produits complémentaires</OPTION>
		  <OPTION VALUE='Rosiers'>Rosiers</OPTION>
		  <OPTION VALUE='Vivaces'>Vivaces</OPTION>
		  <OPTION VALUE=''></OPTION>
	 	</SELECT>
	      </span><br/>
              <span><label><?php echo "Commentaire "; ?></label><input size=30 name="commentaire" type="text" value ="<?php echo"$commentaire" ?>"></span><br/>
            </fieldset>
	    <fieldset>
	      <legend>Caracteristiques</legend>
	      <span><label><?php echo "Cont. "; ?></label><input size=4 name="contenance" type="text" value ="<?php echo"$contenance" ?>"></span><br/>
	      <span><label><?php echo "Pays d'origine "; ?></label><input size=4 name="phyto" type="text" value ="<?php echo"$phyto" ?>"></span><br/>
	      <span><label><?php echo "$lang_taille "; ?></label><input size=6 name="taille" type="text" value ="<?php echo"$taille" ?>"></span><br/>
              <span><label><?php echo "$lang_conditionnement "; ?></label>
		<SELECT NAME='conditionnement'>
		  <OPTION VALUE='<?php echo"$conditionnement" ?>'><?php echo"$conditionnement" ?></OPTION>
		  <OPTION VALUE='conteneur'>conteneur</OPTION>
		  <OPTION VALUE='motte'>motte</OPTION>
		  <OPTION VALUE='motte en pot'>motte en pot</OPTION>
		  <OPTION VALUE='motte d35'>motte d35</OPTION>
		  <OPTION VALUE='motte d40'>motte d40</OPTION>
		  <OPTION VALUE='motte d45'>motte d45</OPTION>
		  <OPTION VALUE='motte d60'>motte d60</OPTION>
		  <OPTION VALUE='motte d70'>motte d70</OPTION>
		  <OPTION VALUE='motte d80'>motte d80</OPTION>
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
	      <span><label><?php echo "%TVA "?></label><input size=3 name="tva" type="text" value ="<?php echo"$tva" ?>"></span><br/>
        <br/>
	      <span><label><?php echo "Part:Tarif TTC " ?></label><input size=5 name="prix_part_ttc" type="text"  value ="<?php echo"$prix_part_ttc" ?> <?php echo "$devise" ?>"></span><br/>
	      <span><label><?php echo "Part : %TVA "?></label><input size=3 name="tva_part" type="text" value ="<?php echo"$tva_part" ?>"></span><br/>
              <span><label><?php echo "Prix achat :  "?></label><input size=3 name="prix_achat" type="text" value ="<?php echo"$prix_achat" ?>"></span><br/>
	    </fieldset>

	    <fieldset>
	      <legend>Stock</legend>
	      <span><label><?php echo "$lang_stock "; ?></label><input size=8 name="stock" type="text" value ="<?php echo"$stock" ?>"></span><br/>
	      <span><label>Quantité disponible : </label><input size=8 name="stock_disponible" type="text" value ="<?php echo"$stock_disponible" ?>"></span><br/>
              <span><label>Localisation : </label><input size=8 name="localisation" type="text" value ="<?php echo"$localisation" ?>"></span><br/>
              
	      <span><label><?php echo "Origine "; ?></label>
		<?php
		   $rqSql = "SELECT id_cat, categorie FROM " . $tblpref ."categorie"
                           . " WHERE categorie <> ''"
                           . " ORDER BY id_cat DESC";
		   $result = mysql_query( $rqSql ) or die( "Execution impossible."); ?>
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
