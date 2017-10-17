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
?>
    <script type='text/javascript'>
        function addFields(){
            // Number of inputs to create
            var number = document.getElementById("member").value;
            // Container <div> where dynamic content will be placed
            var container = document.getElementById("container");
            // Clear previous contents of the container
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            for (i=0;i<number;i++){
                // Append a node with a random text
                container.appendChild(document.createTextNode("Numéro et nombre " + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = "num[]"
                container.appendChild(input);
                
                var input = document.createElement("input");
                input.type = "text";
                input.name = "nb[]";
                container.appendChild(input);
                
                var input = document.createElement("input");
                input.type = "text";
                input.name = "price[]";
                container.appendChild(input);
                
                // Append a line break 
                container.appendChild(document.createElement("br"));
            }
        }
    </script>
<?php


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
<form action="upload_etiquette.php" method="post" enctype="multipart/form-data">
    
    <table>
        <tr>
            <td>
                Selectionner le fichier CSV à importer:
                <input type="file" name="fileToUpload" id="fileToUpload">
            </td>
            <td>
                <input type="text" id="member" name="member" value="">Number of members: (max. 10)<br />
                <a href="#" id="filldetails" onclick="addFields()">Fill Details</a>
                <div id="container"/>
            </td>
            <td>                
                <input type="submit" value="Envoyer fichier" name="submit">            
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
