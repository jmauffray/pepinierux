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
echo "<link rel='stylesheet' type='text/css' href='../include/themes/default/style.css'>";
echo'<link rel="shortcut icon" type="image/x-icon" href="../image/favicon.ico" >';
echo '<table width="100%" border="1" cellpadding="0" cellspacing="0" summary="">';
echo '<tr><td class ="install"><img src="../image/factux.gif" alt=""><br><IMG SRC="../image/spacer.gif" WIDTH=150 HEIGHT=400 ALT=""><br></th><td>';

$zero=isset($_POST['zero'])?$_POST['zero']:"";
$un=isset($_POST['un'])?$_POST['un']:"";
$deux=isset($_POST['deux'])?$_POST['deux']:"";
$trois=isset($_POST['trois'])?$_POST['trois']:"";
$quatre=isset($_POST['quatre'])?$_POST['quatre']:"";
$cinq=isset($_POST['cinq'])?$_POST['cinq']:"";
$six=isset($_POST['six'])?$_POST['six']:"";
$sept=isset($_POST['sept'])?$_POST['sept']:"";
$huit=isset($_POST['huit'])?$_POST['huit']:"";
$euro= '&euro;';
$huit = ereg_replace('�', $euro, $huit);
$huit = ereg_replace('�', $euro, $huit);
$type_fin ='?>';
$com= '//common.php cr�� gr�ce � l\'installeur de Factux soyez prudent si vous l\'�ditez' . "\n";
$zero = '"'.$zero.'";//Nom de l\'entreprise' . "\n";
$un = '"'.$un.'";//Si�ge social de l\'entreprise' . "\n";
$deux = '"'.$deux.'";//num�ro de tel. de l\'entreprise' . "\n";
$trois = '"'.$trois.'";//num�ro de T.V.A. de l\'entreprise' . "\n";
$quatre = '"'.$quatre.'";//Compte en banque de l\'entreprise ' . "\n";
$cinq = '"'.$cinq.'";//slogan de l\'entreprise' . "\n";
$six = '"'.$six.'";//Registre de commerce de l\'entreprise' . "\n";
$sept = '"'.$sept.'";//adresse email' . "\n";
$huit = '"'.$huit.'";//devise utilis�e par Factux' . "\n";

$type = '<?php' . "\n";
$monfichier = fopen("../include/config/var.php", "w+"); 
fwrite($monfichier, ''.$type.''.$com.'$entrep_nom= '.$zero.'$social= '.$un.'$tel= '.$deux.'$tva_vend= '.$trois.'$compte= '.$quatre.'$slogan= '.$cinq.'$reg= '.$six.'$mail= '.$sept.'$devise= '.$huit.'');
echo "<center><br><br><b><font color= green> Vos donn�es ont �t� enregistr�es avec succ�s dans le fichier <font color=red>/factux/include/config/var.php<font color=green>.<br>En cas d'erreur, vous avez 2 choix : recommencer l'installeur de Factux ou �diter ce fichier.<br>Ce fichier est largement comment� (en francais) pour vous y aider.<br>";
fclose($monfichier);
echo "Si la base de donn�es existe <a href='table_create.php'>cliquez ici</a>Si l'installeur de Factux doit la cr�er, <a href='db_create.php'>cliquez ici</a><hr>";
?> 
