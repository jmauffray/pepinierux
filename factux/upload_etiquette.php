<?php

/*
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 */
require_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/config/var.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
include_once("ean13.php");

error_reporting(E_ALL);

function generate_csv($num, $nb, $price, $csvFilename) {

//output
    $today = date("Y-m-d-H-i-s");
    $filename = "uploads/glabels-" . $today . ".csv";
    $fp = fopen($filename, 'w');

    foreach (array_combine($num, $nb) as $numVal => $nbVal) {

        global $tblpref;
        if (!empty($numVal)) {
            echo "num:" . $numVal . " - > " . $nbVal . "<br/>";

            $article = isset($_GET['article']) ? $_GET['article'] : "";
            $sql = "SELECT * FROM " . $tblpref . "article  left join " . $tblpref . "categorie on " . $tblpref . "article.cat = " . $tblpref . "categorie.id_cat
																   WHERE num=$numVal";

            $req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
            while ($data = mysql_fetch_array($req)) {
                $article = $data['article'];
                $variete = $data['variete'];
                $prix_part_ttc = $data['prix_ttc_part'];
                $taille = $data['taille'];
                $descriptif = "descriptif";
            }
            
            for ($i = 0; $i < intval($nbVal); $i++) {
                $columns = array($article, $variete, $taille, $prix_part_ttc, $descriptif, 300000000000 + intval($numVal));
                fputcsv($fp, $columns);
            }
        }
    }

    return $filename;
}

function print_glabels($csvFilename, $modelFilename) {
    //echo "Print glabels : " . $csvFilename . " with model " . $modelFilename;
    $cmd = "export LD_LIBRARY_PATH=\"\" && cd /home/jm/workspace/pepinierux/factux && glabels-3-batch -i " . $csvFilename . " -o uploads/test.pdf " . $modelFilename . " 2>&1";
    //$cmd = "cd /home/jm/workspace/pepinierux/factux && pwd";
    //$cmd = "echo \$LD_LIBRARY_PATH";
    //echo $cmd;
//    glabels-3-batch -i ../workspace/pepinierux/factux/uploads/glabels-2017-10-16-23-23-57.csv -o test.pdf glabels-factux-test.glabels 

    $output = shell_exec($cmd);
    //echo "<pre>$output</pre>";
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);


$num = isset($_POST['num']) ? $_POST['num'] : "";
$nb = isset($_POST['nb']) ? $_POST['nb'] : "";
$price = isset($_POST['price']) ? $_POST['price'] : "";

if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
    //echo "Le fichier est valide, et a ete telecharge
    //avec succes. aa  L'import de l'inventaire commence.</br>";
} else {
    //echo "Pas de fichier\n";
}

$filename = generate_csv($num, $nb, $price, "test.csv");

print_glabels($filename, $target_file);

//HTTP header
header('Content-type: application/pdf');
header("Content-Disposition: attachment; filename=test.pdf");
readfile("uploads/test.pdf")
?>
