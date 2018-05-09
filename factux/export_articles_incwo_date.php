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
<h2>Exporter plantes Incwo</h2>
<form action="export_articles_incwo.php" method="post">
    
    <table>
        <tr>
            <td>
                Selectionner la date minimum de modification des plantes (2018-02-02 ou 2018-02-02 15:02:01) ou vide pour toutes les plantes:
                <input type="datetime-local" name="date_modifie" id="datetime">
            </td>
            <td>                
                <input type="submit" value="Exportert fichier" name="submit">            
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
