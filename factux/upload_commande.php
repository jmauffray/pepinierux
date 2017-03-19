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
include_once("bon_suite_util.php");

error_reporting(E_ALL);

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
    echo "Le fichier est valide, et a ete telecharge
	avec succes. La creation du bon de commande commence.</br>";
} else {
    echo "Pas de fichier\n";
}

//create bon
$clientNum =isset($_POST['client'])?$_POST['client']:"1";
$date =isset($_POST['date'])?$_POST['date']:"2017-01-01";
$type =isset($_POST['type'])?$_POST['type']:"particulier";
$sql1 = "INSERT INTO " . $tblpref . "bon_comm(client_num, date) VALUES (".$clientNum.", '".$date."')";
mysql_query($sql1) or die('Erreur SQL !<br>' . $sql1 . '<br>' . mysql_error());

$row = 1;
if (($handle = fopen($target_file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($row == 1) {
            //ignore first line
            //continue;
        }
        $num = count($data);
        $row++;

        for ($c = 0; $c < $num; $c++) {
            //echo $data[$c] . "<br />\n";
        }

        $article = $data[0];
        $quanti = $data[1];
        $prix_remise = $data[2];

        $volume_pot = 0;
        $remise = 0;
        $lot1="";

        $prixHtvaColumn = getPrixHtvaColumn($type, $quanti);

        $max = addArticleInBon(
                $quanti, $remise, $prix_remise, $volume_pot, $article, $prixHtvaColumn, $type, $lot1);
        echo "Ajout de l'article : ",$article," : x", $quanti," - ", $prix_remise, "e dans le bon ", $max ,"</br>";
    }
    fclose($handle);
}

include_once("include/bas.php");
?>
</td></tr>
</table>
</body>
</html>
