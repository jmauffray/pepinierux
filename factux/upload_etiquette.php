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

function generate_csv($num, $nb, $price) {

//output
    $today = date("Y-m-d-H-i-s");
    $filename = "uploads/glabels-" . $today . ".csv";
    $fp = fopen($filename, 'w');

    for ($i = 0; $i < count($num); $i++) {
    //foreach (array_combine($num, $nb) as $numVal => $nbVal) {

        global $tblpref;
        if (!empty($num[$i])) {

            $article = isset($_GET['article']) ? $_GET['article'] : "";
            $sql = "SELECT * FROM " . $tblpref . "article  left join " . $tblpref . "categorie on " . $tblpref . "article.cat = " . $tblpref . "categorie.id_cat
																   WHERE num=$num[$i]";

            $req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
            while ($data = mysql_fetch_array($req)) {
                $article = $data['article'];
                $variete = $data['variete'];
                $prix_part_ttc = $data['prix_ttc_part'];
                $taille = $data['taille'];
                $descriptif = $data['description'];
                $cond = $data['conditionnement'];
            }
            
            if (!empty($price[$i])) {
                $prix_part_ttc = $price[$i];
            }
            
            
            //toujours ajout de 2 décimales
            $prix_part_ttc = number_format($prix_part_ttc, 2);

            for ($j = 0; $j < intval($nb[$i]); $j++) {
                $columns = array($article, $variete, $taille, $prix_part_ttc, $descriptif, 300000000000 + intval($num[$i]), $cond);
                fputcsv($fp, $columns);
            }
        }
    }

    return $filename;
}

function print_glabels($csvFilename, $modelFilename) {
    
    $cmd = "export LD_LIBRARY_PATH=\"\" && glabels-3-batch -i " . escapeshellarg($csvFilename) . " -o uploads/test.pdf " . escapeshellarg($modelFilename) . " 2>&1";
    
    $output = shell_exec($cmd);
    
    if (file_exists("uploads/test.pdf")) {
        return true;
    } else {
        error_log("Error to generate pdf<br/>");
        error_log($cmd);
        error_log('<pre>' . $output . '</pre>');
        return false;
    }
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

$num = isset($_POST['num']) ? $_POST['num'] : "";
$nb = isset($_POST['nb']) ? $_POST['nb'] : "";
$price = isset($_POST['price']) ? $_POST['price'] : "";

if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {

    $filename = generate_csv($num, $nb, $price);

    if (print_glabels($filename, $target_file)) {
        //HTTP header
        header('Content-type: application/pdf');
        header("Content-Disposition: attachment; filename=etiquette_factux.pdf");
        readfile("uploads/test.pdf");
        unlink("uploads/test.pdf");
    } else {
        header('Content-type: application/csv');
        header("Content-Disposition: attachment; filename=" . $filename);
        readfile($filename);
    }
        
    //clean files
    unlink($filename);
    unlink($target_file);
    
} else {
    echo "Aucun fichier gLabels sélectionné\n";
}
?>
