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
 ?> 
<link rel='stylesheet' type='text/css' href='../include/themes/default/style.css'>
<link rel="shortcut icon" type="image/x-icon" href="../image/favicon.ico" >

<h2>Installation de Factux suite</h2>
<p>Nous allons maintenant enregistrer les donn�es qui figureront sur vos bons de commande et factures</p>

<hr><center><br>
  
  
    
		<table width="100%" border="1" cellpadding="0" cellspacing="0" summary="">
     <font color =green>
		    <tr><td><form action="setup_3.php" method="post" name="artice" id="artice">Nom de l'entreprise
        <td><input name="zero" type="text" id="article" maxlength="140"></tr>        
        <tr><td>Si�ge social de l'entreprise
        <td><input name="un" type="text" id="article" maxlength="140"></tr>
        <tr><td>Num�ro de t�l�phone de l'entreprise
				<td><input name="deux" type="text"  ></tr>
				<tr><td>Num�ro de T.V.A. de l'entreprise
        <td><input name="trois" type="text" ></tr>
        <tr><td>Num�ro de compte en banque de l'entreprise
        <td><input name="quatre" type="text" ></tr>
        <tr><td>Slogan de l'entreprise (Faites assez court)
        <td><input name="cinq" type="text" ></tr>
				<tr><td>Num�ro de registre de commerce de l'entreprise
				<td><input name="six" type="text" ></tr>
				<tr><td>Adresse email de l'entreprise
        <td><input name="sept" type="text" ></tr>
				<tr><td>Devise utilis�e par Factux (&euro;, $...)
				<td><input name="huit" type="text" ></tr>
       
        <tr><td><input type="submit" name="Submit" value="Envoyer">
        <td><input name="reset" type="reset" id="reset" value="effacer"></tr>
    </form>
     
</table><br><hr>
</body>
</html>