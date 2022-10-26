<?php

/*
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 */
require_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
include_once("ean13.php");

//display PHP errors
error_reporting(E_ALL);

//constants
$CODE_COMPTABLE_ARTICLE = "70452";
$CODE_COMPTABLE_TVA = "4457106";
$CODE_COMPTABLE_CLIENT = "4119";

$JOURNAL_ACHAT = "ACH";
$JOURNAL_VENTE = "VEN";
$JOURNAL_REMISE = "REM";
$JOURNAL_CAISSE = "CAI";

$CODE_TVA = array(
    '0'    => '0',
    '2.1'  => '2',
    '5.5'  => '1',
    '7'    => '7',
    '10'   => '3',
    '19.6' => '5',
    '20'   => '6'
);

$SEP = ";";

$CLIENT_IGNORE = array(
    'Pépinière',
    'Pépinières',
    'pépinière',
    'pépinières',
    'SARL',
    'EURL',
    'SCOP',
    'SAS',
    'SCEA',
    'Camping',
    'Société',
    'Jardin',
    'Jardins',
    'mairie',
    'Mairie',
    'Le',
    'Les',
    'Le',
    'des',
    'de',
);

$NEGOCE_CAT = 7;

//get num modifie
$num = isset($_POST['num']) ? $_POST['num'] : "";

//output
$filename = "facture-export-" . $num . ".csv";
$fp = fopen('php://output', 'w');

//HTTP header
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

//first line
fwrite($fp, "#journal;date:compte;reference;libelle;debit;credit;nombre;quantite\n");

//get info from facture
$sql = "SELECT date_fact, list_num FROM " . $tblpref . "facture WHERE num = $num";
$req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
while ($data = mysql_fetch_array($req, MYSQL_ASSOC)) {
    $list_num = unserialize($data['list_num']);
    $num_bon = $list_num[0];
    $date_fact = $data['date_fact'];
    $date_fact_format = substr($date_fact, 8, 2) . substr($date_fact, 5, 2) . substr($date_fact, 0, 4);
}

//get info from bon
$sql = "SELECT SUM(to_tva_art), SUM(tot_art_htva), taux_tva, nom " .
    "FROM " . $tblpref . "client " .
    "RIGHT JOIN " . $tblpref . "bon_comm on " . $tblpref . "client.num_client = " . $tblpref . "bon_comm.client_num " .
    "LEFT join " . $tblpref . "cont_bon on " . $tblpref . "bon_comm.num_bon = " . $tblpref . "cont_bon.bon_num " .
    "LEFT JOIN  " . $tblpref . "article on " . $tblpref . "article.num = " . $tblpref . "cont_bon.article_num " .
    "WHERE " . $tblpref . "bon_comm.num_bon=$num_bon AND  " . $tblpref . "article.cat != $NEGOCE_CAT " .
    "GROUP BY taux_tva";

$req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
while ($data = mysql_fetch_array($req, MYSQL_ASSOC)) {

    $taux_tva = $data['taux_tva'];

    //client
    $nom_client = $data['nom'];
    $nom_client_array = explode(" ", $nom_client);
    $result = array_diff($nom_client_array, $CLIENT_IGNORE);
    $result = implode(" ", $result);
    $nom_client = substr($result, 0, 5);

    $total_ht = $data['SUM(tot_art_htva)'];
    fwrite(
        $fp,
        $JOURNAL_VENTE . $SEP .
            $date_fact_format . $SEP .
            $CODE_COMPTABLE_ARTICLE . $CODE_TVA[$taux_tva] . $SEP .
            $num . $SEP .
            "CLIENT VENTE ARTICLE" . $SEP .
            $SEP .
            $total_ht . $SEP
            . "\n"
    );

    $total_tva = $data['SUM(to_tva_art)'];
    fwrite(
        $fp,
        $JOURNAL_VENTE . $SEP .
            $date_fact_format . $SEP .
            $CODE_COMPTABLE_TVA . $CODE_TVA[$taux_tva] . $SEP .
            $num . $SEP .
            "CLIENT TVA " . $taux_tva . "%" . $SEP .
            $SEP .
            $total_tva . $SEP
            . "\n"
    );

    $total = $total_ht + $total_tva;
    fwrite(
        $fp,
        $JOURNAL_VENTE . $SEP .
            $date_fact_format . $SEP .
            $CODE_COMPTABLE_CLIENT . $nom_client . $SEP .
            $num . $SEP .
            "CLIENT TTC" . $SEP .
            $SEP .
            $total . $SEP
            . "\n"
    );
}

//get info from bon for negoce
$sql = "SELECT SUM(to_tva_art), SUM(tot_art_htva), taux_tva, nom " .
    "FROM " . $tblpref . "client " .
    "RIGHT JOIN " . $tblpref . "bon_comm on " . $tblpref . "client.num_client = " . $tblpref . "bon_comm.client_num " .
    "LEFT join " . $tblpref . "cont_bon on " . $tblpref . "bon_comm.num_bon = " . $tblpref . "cont_bon.bon_num " .
    "LEFT JOIN  " . $tblpref . "article on " . $tblpref . "article.num = " . $tblpref . "cont_bon.article_num " .
    "WHERE " . $tblpref . "bon_comm.num_bon=$num_bon AND  " . $tblpref . "article.cat = $NEGOCE_CAT " .
    "GROUP BY taux_tva";

$req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
while ($data = mysql_fetch_array($req, MYSQL_ASSOC)) {

    $taux_tva = $data['taux_tva'];

    //client
    $nom_client = $data['nom'];
    $nom_client_array = explode(" ", $nom_client);
    $result = array_diff($nom_client_array, $CLIENT_IGNORE);
    $result = implode(" ", $result);
    $nom_client = substr($result, 0, 5);

    $total_ht = $data['SUM(tot_art_htva)'];
    fwrite(
        $fp,
        $JOURNAL_VENTE . $SEP .
            $date_fact_format . $SEP .
            $CODE_COMPTABLE_ARTICLE . $CODE_TVA[$taux_tva] . $SEP .
            $num . $SEP .
            "CLIENT VENTE ARTICLE" . $SEP .
            $SEP .
            $total_ht . $SEP
            . "\n"
    );

    $total_tva = $data['SUM(to_tva_art)'];
    fwrite(
        $fp,
        $JOURNAL_VENTE . $SEP .
            $date_fact_format . $SEP .
            $CODE_COMPTABLE_TVA . $CODE_TVA[$taux_tva] . $SEP .
            $num . $SEP .
            "CLIENT TVA " . $taux_tva . "%" . $SEP .
            $SEP .
            $total_tva . $SEP
            . "\n"
    );

    $total = $total_ht + $total_tva;
    fwrite(
        $fp,
        $JOURNAL_VENTE . $SEP .
            $date_fact_format . $SEP .
            $CODE_COMPTABLE_CLIENT . $nom_client . $SEP .
            $num . $SEP .
            "CLIENT TTC" . $SEP .
            $SEP .
            $total . $SEP
            . "\n"
    );
}

/*
SELECT SUM(to_tva_art), SUM(tot_art_htva), taux_tva
FROM factux2_client
RIGHT JOIN factux2_bon_comm on factux2_client.num_client = factux2_bon_comm.client_num 
LEFT join factux2_cont_bon on factux2_bon_comm.num_bon = factux2_cont_bon.bon_num 
LEFT JOIN  factux2_article on factux2_article.num = factux2_cont_bon.article_num 
WHERE factux2_bon_comm.num_bon=4233 AND  factux2_article.cat != 7
GROUP BY taux_tva

SELECT SUM(to_tva_art), SUM(tot_art_htva), taux_tva
FROM factux2_client
RIGHT JOIN factux2_bon_comm on factux2_client.num_client = factux2_bon_comm.client_num 
LEFT join factux2_cont_bon on factux2_bon_comm.num_bon = factux2_cont_bon.bon_num 
LEFT JOIN  factux2_article on factux2_article.num = factux2_cont_bon.article_num 
WHERE factux2_bon_comm.num_bon=4233 AND  factux2_article.cat=7
GROUP BY taux_tva
*/

fclose($fp);
