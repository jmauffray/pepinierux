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
 * File Name: edit_bon.php
 * 	apel au formulaire d'edition du bon de commande
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
echo '<link rel="stylesheet" type="text/css" href="include/style.css">';

if($num_bon==''){
$num_bon=isset($_GET['num_bon'])?$_GET['num_bon']:"";
$nom=isset($_GET['nom'])?$_GET['nom']:"";
}
?>
</td>
</tr>
<tr>
<td  class="page" align="center">
<SCRIPT language="JavaScript" type="text/javascript">
		function confirmDelete()
		{
		var agree=confirm("<?php echo $lang_sup_li; ?>");
		if (agree)
		 return true ;
		else
		 return false ;
		}
		</script>
		<?php
		include ("form_editer_bon.php");


include_once("include/bas.php");
?>
</body>
</html>
