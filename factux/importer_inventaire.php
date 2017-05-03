<?php 
/*
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 *.
 */
require_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
include_once("include/headers.php");
include_once("include/finhead.php");
?>
<table width="760" border="0" class="page" align="center">
<tr>
<td class="page" align="center">
<?php
include_once("include/head.php");
?>
</td>
</tr>
<tr>
<td  class="page" align="center">
<h2>Importer inventaire</h2>
<form action="upload_inventaire.php" method="post" enctype="multipart/form-data">
    
    <table>
        <tr>
            <td>
                Selectionner le fichier CSV à importer:
                <input type="file" name="fileToUpload" id="fileToUpload">
            </td>
            <td>                
                <input type="submit" value="Envoyer fichier" name="submit">            
            </td>
        </tr>
        <tr>
            <td>
                Fichier CSV exemple:
            </td>
            <td>                
                <a href="inventaire_exemple.csv">Exemple CSV</a>
            </td>
        </tr>
    </table>
    
</form>

<?php
include_once("include/bas.php");
?>
</td></tr>
</table>
</body>
</html>
