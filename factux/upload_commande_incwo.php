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
    echo "Le fichier Incwo est valide, et a ete telecharge
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
        
        $offset = 2;
        $article = intval($data[7 + $offset]);
        $quanti = intval($data[12 + $offset]);
        $prix_remise_ht = $data[19 + $offset];
        $prix_remise_1_art_ht = 0;
        if ($quanti > 0) {
            $prix_remise_1_art_ht = ($prix_remise_ht / $quanti);
        }
        $tva = substr($data[13 + $offset], 0, -1);
        
        //compute price with tva
        $prix_remise_1_art_ttc = ((1 + ($tva / 100)) * $prix_remise_1_art_ht);
        
        //avoir, multiply price per -1
        $isFacture = $data[3 + $offset];
        if($isFacture != "1")
        {
            $prix_remise_1_art_ttc = $prix_remise_1_art_ttc * -1;
        }
        
        $volume_pot = 0;
        $remise = 0;
        $lot1="";

        $max = getLastNumBon();
        addArticleInBon(
                $max, $quanti, $remise, $prix_remise_1_art_ttc, $volume_pot, $article, $type, $lot1);
        echo "Ajout de l'article : ",$article," : x", $quanti," - ", $prix_remise_1_art_ttc, "e TTC, TVA:", $tva," unitaire dans le bon ", $max ,"</br>";
    }
    fclose($handle);
}

include_once("include/bas.php");
?>
</td></tr>
</table>
</body>
</html>
