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
 * File Name: verif.php
 * 	Fichier de cr�ation et verification de la session
 * 
 * * Version:  1.1.5
 * Modified: 11/04/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
ini_set('session.save_path', 'include/session');
session_start();



if($_SESSION['trucmuch']=='')
{
echo "Vous n'�tes pas autoris� � acc�der � cette zone";
include('login.inc.php');
exit;
}
$utili = $_SESSION['trucmuch'];
$lang = $_SESSION['lang'];	
 if ($lang=='') { 
$lang ="fr";  
}		

include_once("include/config/common.php");
$sqlz = "SELECT * FROM " . $tblpref ."user WHERE " . $tblpref ."user.login = \"$utili\"";
$req = mysql_query($sqlz) or die('Erreur SQL !<br>'.$sqlz.'<br>'.mysql_error());
while($data = mysql_fetch_array($req))
{
  $user_num = $data['num'];
  $user_nom = $data["nom"];
  $user_prenom = $data["prenom"];
  $user_email = $data['email'];
	$user_fact = $data['fact'];
	$user_com = $data['com'];
	$user_dev = $data['dev'];
	$user_admin = $data['admin'];
	$user_dep = $data['dep'];
	$user_stat = $data['stat'];
	$user_art = $data['art'];
	$user_cli = $data['cli'];

	}

?>
