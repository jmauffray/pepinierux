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
<h2>Exporter facture générique</h2>
<form action="export_facture_generique.php" method="post">
    
    <table>
        <tr>
            <td>
                Selectionner le numéro de facture
                <input name="num" id="num">
            </td>
            <td>                
                <input type="submit" value="Exporter facture" name="submit">            
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
