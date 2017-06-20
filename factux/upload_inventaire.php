<?php
/*
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 */
require_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
include_once("include/headers.php");
include_once("include/finhead.php");

error_reporting(E_ALL);

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
    echo "Le fichier est valide, et a ete telecharge
	avec succes. L'import de l'inventaire commence.</br>";
} else {
    echo "Pas de fichier\n";
}

$row = 0;
if (($handle = fopen($target_file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        ++$row;
        if ($row == 1) {
            //ignore first line
            continue;
        }
        $num = count($data);
        
        for ($c = 0; $c < $num; $c++) {
            //echo $data[$c] . "<br />\n";
        }

        $article = $data[0];
        $quanti = $data[1];
        $quantiVente = $data[2];
        $loc = $data[3];
        
        $sql1 = "UPDATE `" . $tblpref . "article` SET `stock` = '" . $quanti . "',  `stock_disponible` = '" . $quantiVente . "',  `localisation` = '" . $loc . "' "
                . "WHERE `" . $tblpref . "article`.`num` = " . $article;
        mysql_query($sql1) or die('Erreur SQL !<br>' . $sql1 . '<br>' . mysql_error());

        echo "Mise à jour de l'article : ",$article,", quantité : ", $quanti,"</br>";
    }
    fclose($handle);
}

include_once("include/bas.php");
?>
</td></tr>
</table>
</body>
</html>
