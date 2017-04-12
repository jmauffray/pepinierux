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
 * File Name: head.php
 * 	Fichier d'entete des pages.
 * 
 * * Version:  1.1.4
 * * Modified: 25/04/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
include_once("include/config/common.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
echo '<link rel="stylesheet" type="text/css" href="include/style.css">';

    //Efface les fichiers temporaires
		$dir = fpdf ;
    $t=time();
    $h=opendir($dir);
    while($file=readdir($h))
    {
        if(substr($file,0,3)=='tmp' and substr($file,-4)=='.pdf')
        {
            $path=$dir.'/'.$file;
            if($t-filemtime($path)>3)
                @unlink($path);
        }
    }
    closedir($h);


$filename = 'installeur';

if (file_exists($filename)) {
   echo "<center><h1>$lang_erreur_insta</h1><br>";
}

if (file_exists('dump/backup.sql')) {
   echo "<center><h1>$lang_erreur_backup</h1><br>";
}


if (is_writable("include/config/common.php")) {
echo "<center><h1> $lang_erreur_common</h1><br>";
}

if (is_writable("include/config/var.php")) {
echo "<center><h1>$lang_erreur_var</h1><br>";
}
 ?> 
 <!--- Factux le facturier libre, Copyright (C) 2003-2004 Guy Hendrickx, Licensed under the terms of the GNU  General Public License: http://www.opensource.org/licenses/gpl-license.php .For further information visit: http://factux.sourceforge.net -->

<center><script type="text/javascript" LANGUAGE="JavaScript">
<!-- debut du script
   var csChaine;
   var nDay, nJour, nMois, nAnnee;
   var dtJour;
   var NomMois = new Array   ('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
   var NomJour = new Array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
   csChaine = " ";
   dtJour   = new Date();
   nDay     = dtJour.getDay();
   nJour    = dtJour.getDate();
   nMois    = dtJour.getMonth() ;
   nAnnee   = dtJour.getYear();
   csChaine += " " + NomJour[nDay]  + " ";
   csChaine += nJour;
   csChaine += " " + NomMois[nMois] + " ";

   if (nAnnee <= 199) nAnnee += 1900;
           csChaine += nAnnee + " ";
   document.write( csChaine );
// fin du script -->
</script>
<script language="javascript" src="include/menu.js"></script>
<link rel="stylesheet" type="text/css" href="include/head.css">

<div id="conteneurmenu">
<script language="Javascript" type="text/javascript">
preChargement();
</script>
	<p id="menu2" class="menu"
		onmouseover="MontrerMenu('ssmenu2');"
		onmouseout="CacherDelai();">
      <a href="#" onfocus="MontrerMenu('ssmenu2');"><img border = 0 src= image/commande.gif><br>
			<?php echo $lang_commandes; ?><span>&nbsp;:</span></a>
    </p>
    <ul id="ssmenu2" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
		<?php if ($user_com != 'n') { ?>
	<li><a href="form_commande.php"><?php echo $lang_creer ?><span>&nbsp;;</span></a></li>
	<li><a href="lister_commandes.php"><?php echo $lang_lister ?><span>&nbsp;;</span></a></li>
	<li><a href="chercher_commande.php"><?php echo $lang_cherc ?><span>&nbsp;;</span></a></li>
	<li><a href="lister_commandes_non_facturees.php"><?php echo $lang_non_facu ?><span>&nbsp;;</span></a></li>
	<?php } ?>
	</ul>
<!--------------------------------------------->
    <p id="menu3" class="menu"
		onmouseover="MontrerMenu('ssmenu3');"
		onmouseout="CacherDelai();">
      <a href="#" onfocus="MontrerMenu('ssmenu3');"><img border = 0 src= image/facture.gif><br>
			<?php echo $lang_factures; ?> &nbsp; <span>&nbsp;:</span></a>
    </p>
    <ul id="ssmenu3" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
	<?php if ($user_fact != 'n') { ?>
	<li><a href="form_facture.php"><?php echo $lang_creer ?><span>&nbsp;;</span></a></li>
	<li><a href="lister_factures.php"><?php echo $lang_lister ?><span>.</span></a></li>
	<li><a href="chercher_factures.php"><?php echo $lang_cherc ?><span>.</span></a></li>
	<li><a href="lister_factures_non_reglees.php"><?php echo $lang_non_reg ?><span>.</span></a></li>
    <?php } ?>
    </ul>
<!--------------------------------------------->
    <p id="menu4" class="menu"
		onmouseover="MontrerMenu('ssmenu4');"
		onmouseout="CacherDelai();">
		<a href="#" onfocus="MontrerMenu('ssmenu4');"><img border = 0 src= image/depense.gif><br>
		<?php echo $lang_depenses; ?> &nbsp; <span>&nbsp;:</span></a>
    </p>
    <ul id="ssmenu4" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
		<?php if ($user_dep != 'n') { ?>
	<li><a href="form_depenses.php"><?php echo $lang_creer ?><span>&nbsp;;</span></a></li>
	<li><a href="lister_depenses.php"><?php echo $lang_lister ?><span>&nbsp;;</span></a></li>
	<li><a href="chercher_dep.php"><?php echo $lang_cherc ?><span>&nbsp;;</span></a></li>
	<li><a href="stat_depenses_mois.php"><?php echo $lang_depenses_par_fournisseur_mois; ?><span>&nbsp;;</span></a></li>
	<li><a href="stat_depenses_annee.php"><?php echo $lang_depenses_par_fournisseur_mois_annee ?><span>&nbsp;;</span></a></li>
    <?php } ?>
    </ul>
<!--------------------------------------------->
    <p id="menu5" class="menu"
		onmouseover="MontrerMenu('ssmenu5');"
		onmouseout="CacherDelai();">
		<a href="#" onfocus="MontrerMenu('ssmenu5');"><img border = 0 src= image/article.gif><br>
		<?php echo $lang_articles; ?> &nbsp; <span>&nbsp;:</span></a>
    </p>
		<ul id="ssmenu5" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
		<?php if ($user_art != 'n') { ?>
	<li><a href="form_article.php"><?php echo $lang_creer ?><span>&nbsp;;</span></a></li>
	<li><a href="lister_articles.php"><?php echo $lang_lister ?><span>&nbsp;;</span></a></li>
	<?php } ?>
	</ul>
<!--------------------------------------------->
    <p id="menu6" class="menu"
		onmouseover="MontrerMenu('ssmenu6');"
		onmouseout="CacherDelai();">
		<a href="#" onfocus="MontrerMenu('ssmenu5');"><img border = 0 src= image/client.gif><br>
		<?php echo $lang_clients; ?> &nbsp; &nbsp; <span>&nbsp;:</span></a>
    </p>
		<ul id="ssmenu6" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
		<?php if ($user_cli != 'n') { ?>
	<li><a href="form_client.php"><?php echo $lang_creer ?><span>&nbsp;;</span></a></li>
	<li><a href="lister_clients.php"><?php echo $lang_lister ?><span>&nbsp;;</span></a></li>
	<?php } ?>
	</ul>
<!--------------------------------------------->
    <p id="menu9" class="menu"
		onmouseover="MontrerMenu('ssmenu9');"
		onmouseout="CacherDelai();">
		<a href="#" onfocus="MontrerMenu('ssmenu9');"><img border = 0 src= image/outils.gif><br>
		<?php echo $lang_outils; ?> &nbsp; &nbsp <span>&nbsp;:</span></a>
    </p>
    <ul id="ssmenu9" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
		<?php
		if ($user_admin == 'y'){ ?>
	<li><a href="form_utilisateurs.php"><?php echo $lang_aj_utl ?><span>&nbsp;;</span></a></li>
		<li><a href="lister_utilisateurs.php"><?php echo $lang_list_utl ?><span>&nbsp;;</span></a></li>
		<li><a href="form_mailing.php"><?php echo $lang_mainling_list ?><span>&nbsp;;</span></a></li>
	<li><a href="form_backup.php"><?php echo $lang_back_men ?><span>&nbsp;;</span></a></li>
	<?php
	}
	?>
<li><a href="include/calculette.html" onclick="window.open('','popup','width=300,height=220,top=200,left=150,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0')" target="popup"><?php echo $lang_calculette; ?><span>&nbsp;;</span></a></li>
<li><a href="logout.php"><?php echo $lang_sortir ?><span>&nbsp;;</span></a></li>

	</ul>
	<!--------------------------------------------->
    <p id="menu7" class="menu"
		onmouseover="MontrerMenu('ssmenu7');"
		onmouseout="CacherDelai();">
      <a href="#" onfocus="MontrerMenu('ssmenu7');"><img border = 0 src= image/stat.gif><br>
			<?php echo $lang_statistiques; ?> <span>&nbsp;:</span></a>
    </p>
    <ul id="ssmenu7" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
		<?php if ($user_stat != 'n') { ?>
	<li><a href="ca_annee.php"><?php echo $lang_annuelles ?> <span>&nbsp;;</span></a></li>
	<li><a href="ca_parclient.php"><?php echo $lang_ca_cli ?><span>.</span></a></li>
	<li><a href="ca_parclient_1mois.php"><?php echo $lang_cli_moi ?><span>.</span></a></li>
	<li><a href="ca_articles.php"><?php echo $lang_stat_art ?><span>.</span></a></li>
	<li><a href="form_stat_client.php"><?php echo $lang_moi_cli ?><span>.</span></a></li>
	<li><a href="stats_dep.php"><?php echo $lang_depenses_par_fournisseur; ?><span>.</span></a></li>
	<?php } ?>
    </ul>
<!--------------------------------------------->
    <p id="menu1" class="menu"
		onmouseover="MontrerMenu('ssmenu1');"
		onmouseout="CacherDelai();">
      <a href="#" onfocus="MontrerMenu('ssmenu1');"><img border = 0 src= image/devis.gif><br>
	   <?php echo $lang_devis_pluriel; ?> &nbsp;&nbsp;<span>&nbsp;:</span></a>
    </p>
    <ul id="ssmenu1" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
		<?php if ($user_dev != 'n') { ?>
	<li><a href="form_devis.php"><?php echo $lang_creer ?><span>&nbsp;;</span></a></li>
	<li><a href="lister_devis.php"><?php echo $lang_lister ?><span>.</span></a></li>
	<li><a href="chercher_devis.php"><?php echo $lang_cherc ?><span>.</span></a></li>
	<li><a href="devis_non_commandes.php"><?php echo $lang_non_com ?><span>.</span></a></li>
	<?php } ?>
    </ul>
<!--------------------------------------------->

<!--------------------------------------------->
    <p id="menu8" class="menu"
		onmouseover="MontrerMenu('ssmenu8');"
		onmouseout="CacherDelai();">
      <a href="#" onfocus="MontrerMenu('ssmenu8');"><img border = 0 src= image/facture.gif><br>
			<?php echo Lot; ?> &nbsp; <span>&nbsp;:</span></a>
    </p>
    <ul id="ssmenu8" class="ssmenu"
		onmouseover="AnnulerCacher();"
		onmouseout="CacherDelai();"
		onfocus="AnnulerCacher();"
		onblur="CacherDelai();">
	<?php if ($user_fact != 'n') { ?>
	<li><a href="lister_lot.php"><?php echo "Lister" ?><span>&nbsp;;</span></a></li>
	<li><a href="form_lot.php"><?php echo "Créer" ?><span>.</span></a></li>
	<li><a href="form_recherche_lot.php"><?php echo "Rechercher" ?><span>.</span></a></li>
    <?php } ?>
    </ul>
<!--------------------------------------------->

 
</div>
<div id="texte"></div>
<script language="Javascript" type="text/javascript">
centrer_menu = true;
Chargement();</script>
