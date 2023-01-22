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
$CODE_COMPTABLE_ARTICLE = "70102678";
$CODE_COMPTABLE_TVA = "44571060";
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

//functionsgit a
function generate_csv_facture(
    $fp,
    $num,
    $tblpref,
    $SEP,
    $CLIENT_IGNORE,
    $JOURNAL_VENTE,
    $CODE_TVA,
    $CODE_COMPTABLE_ARTICLE,
    $CODE_COMPTABLE_TVA,
    $CODE_COMPTABLE_CLIENT,
    $NEGOCE_CAT
) {
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

    processSql(
        $sql,
        $SEP,
        $num,
        $CLIENT_IGNORE,
        $fp,
        $JOURNAL_VENTE,
        $date_fact_format,
        $CODE_TVA,
        $CODE_COMPTABLE_ARTICLE,
        $CODE_COMPTABLE_TVA,
        $CODE_COMPTABLE_CLIENT
    );

    //get info from bon for negoce
    $sql = "SELECT SUM(to_tva_art), SUM(tot_art_htva), taux_tva, nom " .
        "FROM " . $tblpref . "client " .
        "RIGHT JOIN " . $tblpref . "bon_comm on " . $tblpref . "client.num_client = " . $tblpref . "bon_comm.client_num " .
        "LEFT join " . $tblpref . "cont_bon on " . $tblpref . "bon_comm.num_bon = " . $tblpref . "cont_bon.bon_num " .
        "LEFT JOIN  " . $tblpref . "article on " . $tblpref . "article.num = " . $tblpref . "cont_bon.article_num " .
        "WHERE " . $tblpref . "bon_comm.num_bon=$num_bon AND  " . $tblpref . "article.cat = $NEGOCE_CAT " .
        "GROUP BY taux_tva";

    processSql(
        $sql,
        $SEP,
        $num,
        $CLIENT_IGNORE,
        $fp,
        $JOURNAL_VENTE,
        $date_fact_format,
        $CODE_TVA,
        $CODE_COMPTABLE_ARTICLE,
        $CODE_COMPTABLE_TVA,
        $CODE_COMPTABLE_CLIENT
    );
}

function filterNomClient($nom, $ignore)
{
    $nom_client_array = explode(" ", $nom);
    $result = array_diff($nom_client_array, $ignore);
    $result = implode(" ", $result);
    $result = preg_replace("/[^a-zA-Z]+/", "", $result);

    return strtoupper(substr($result, 0, 5));
}

function processSql(
    $sql,
    $sep,
    $num,
    $ignore,
    $fp,
    $journalVente,
    $date_fact_format,
    $codeTva,
    $codeComptableArticle,
    $codeComptableTva,
    $codeComptableClient
) {
    $req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
    while ($data = mysql_fetch_array($req, MYSQL_ASSOC)) {

        $taux_tva = $data['taux_tva'];
        $nom_client = filterNomClient($data['nom'], $ignore);
        $total_ht = $data['SUM(tot_art_htva)'];
        fwrite(
            $fp,
            $journalVente . $sep .
                $date_fact_format . $sep .
                $codeComptableArticle . $codeTva[$taux_tva] . $sep .
                $num . $sep .
                "CLIENT VENTE ARTICLE" . $sep .
                $sep .
                $total_ht . $sep . $sep
                . "\n"
        );

        $total_tva = $data['SUM(to_tva_art)'];
        fwrite(
            $fp,
            $journalVente . $sep .
                $date_fact_format . $sep .
                $codeComptableTva . $codeTva[$taux_tva] . $sep .
                $num . $sep .
                "CLIENT TVA " . $taux_tva . "%" . $sep .
                $sep .
                $total_tva . $sep . $sep
                . "\n"
        );

        $total = $total_ht + $total_tva;
        fwrite(
            $fp,
            $journalVente . $sep .
                $date_fact_format . $sep .
                $codeComptableClient . $nom_client . $sep .
                $num . $sep .
                "CLIENT TTC" . $sep .
                $total .
                $sep . $sep . $sep
                . "\n"
        );
    }
}

//get num modifie
$num = isset($_POST['num']) ? $_POST['num'] : "";

//output
$filename = "facture-export-" . $num . ".csv";
$fp = fopen('php://output', 'w');

//HTTP header
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

generate_csv_facture(
    $fp,
    $num,
    $tblpref,
    $SEP,
    $CLIENT_IGNORE,
    $JOURNAL_VENTE,
    $CODE_TVA,
    $CODE_COMPTABLE_ARTICLE,
    $CODE_COMPTABLE_TVA,
    $CODE_COMPTABLE_CLIENT,
    $NEGOCE_CAT
);

fclose($fp);
