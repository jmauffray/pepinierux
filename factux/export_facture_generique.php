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
$CODE_COMPTABLE_ARTICLE = array(
    '0'    => '70102678',
    '2.1'  => '70102678',
    '5.5'  => '70102678',
    '7'    => '70102678',
    '10'   => '70102678',
    '19.6' => '70102678',
    '20'   => '70102678'
);

$CODE_COMPTABLE_ARTICLE_NEGOCE = array(
    '0'    => '00000000',
    '2.1'  => '00000000',
    '5.5'  => '70703489',
    '7'    => '00000000',
    '10'   => '70700678',
    '19.6' => '00000000',
    '20'   => '70702678'
);

$CODE_COMPTABLE_ARTICLE_TRANSPORT = array(
    '0'    => '70800678',
    '2.1'  => '70800678',
    '5.5'  => '70800678',
    '7'    => '70800678',
    '10'   => '70800678',
    '19.6' => '70800678',
    '20'   => '70800678'
);

$CODE_COMPTABLE_ARTICLE_PRESTATION = array(
    '0'    => '70600678',
    '2.1'  => '70600678',
    '5.5'  => '70600678',
    '7'    => '70600678',
    '10'   => '70600678',
    '19.6' => '70600678',
    '20'   => '70600678'
);

$CODE_COMPTABLE_CLIENT = "4119";

$CAT_NEGOCE     = 7;
$CAT_TRANSPORT  = 37;
$CAT_PRESTATION = 26;

$JOURNAL_ACHAT  = "ACH";
$JOURNAL_VENTE  = "VEN";
$JOURNAL_REMISE = "REM";
$JOURNAL_CAISSE = "CAI";

$CODE_COMPTABLE_TVA = array(
    '0'    => '0000000',
    '2.1'  => '0000000',
    '5.5'  => '4457106',
    '7'    => '0000000',
    '10'   => '4457110',
    '19.6' => '0000000',
    '20'   => '4457120'
);

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

$ZERO_CHAR = "0";

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

class TotalAndClient {
    public $total;
    public $client;
}

//functionsgit a
function generate_csv_facture(
    $fp,
    $num,
    $tblpref
) {
    global 
    $CODE_COMPTABLE_ARTICLE,
    $CODE_COMPTABLE_ARTICLE_NEGOCE, 
    $CODE_COMPTABLE_ARTICLE_TRANSPORT,
    $CODE_COMPTABLE_ARTICLE_PRESTATION,
    $CAT_NEGOCE,
    $CAT_TRANSPORT,
    $CAT_PRESTATION;
    
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
        "WHERE " . $tblpref . "bon_comm.num_bon=$num_bon " .
        "AND  " . $tblpref . "article.cat NOT IN ( $CAT_NEGOCE, $CAT_PRESTATION, $CAT_TRANSPORT) " .
        "GROUP BY taux_tva";

    $totalAndClient1 = processSql(
        $sql,
        $num,
        $fp,
        $date_fact_format,
        $CODE_COMPTABLE_ARTICLE);

    //get info from bon for negoce
    $sql = "SELECT SUM(to_tva_art), SUM(tot_art_htva), taux_tva, nom " .
        "FROM " . $tblpref . "client " .
        "RIGHT JOIN " . $tblpref . "bon_comm on " . $tblpref . "client.num_client = " . $tblpref . "bon_comm.client_num " .
        "LEFT join " . $tblpref . "cont_bon on " . $tblpref . "bon_comm.num_bon = " . $tblpref . "cont_bon.bon_num " .
        "LEFT JOIN  " . $tblpref . "article on " . $tblpref . "article.num = " . $tblpref . "cont_bon.article_num " .
        "WHERE " . $tblpref . "bon_comm.num_bon=$num_bon " .
        "AND  " . $tblpref . "article.cat = $CAT_NEGOCE " .
        "GROUP BY taux_tva";

    $totalAndClient2 = processSql(
        $sql,
        $num,
        $fp,
        $date_fact_format,
        $CODE_COMPTABLE_ARTICLE_NEGOCE
    );

    //get info from bon for transport
    $sql = "SELECT SUM(to_tva_art), SUM(tot_art_htva), taux_tva, nom " .
        "FROM " . $tblpref . "client " .
        "RIGHT JOIN " . $tblpref . "bon_comm on " . $tblpref . "client.num_client = " . $tblpref . "bon_comm.client_num " .
        "LEFT join " . $tblpref . "cont_bon on " . $tblpref . "bon_comm.num_bon = " . $tblpref . "cont_bon.bon_num " .
        "LEFT JOIN  " . $tblpref . "article on " . $tblpref . "article.num = " . $tblpref . "cont_bon.article_num " .
        "WHERE " . $tblpref . "bon_comm.num_bon=$num_bon " .
        "AND  " . $tblpref . "article.cat = $CAT_TRANSPORT " .
        "GROUP BY taux_tva";

    $totalAndClient3 = processSql(
        $sql,
        $num,
        $fp,
        $date_fact_format,
        $CODE_COMPTABLE_ARTICLE_TRANSPORT
    );

    //get info from bon for prestation
    $sql = "SELECT SUM(to_tva_art), SUM(tot_art_htva), taux_tva, nom " .
        "FROM " . $tblpref . "client " .
        "RIGHT JOIN " . $tblpref . "bon_comm on " . $tblpref . "client.num_client = " . $tblpref . "bon_comm.client_num " .
        "LEFT join " . $tblpref . "cont_bon on " . $tblpref . "bon_comm.num_bon = " . $tblpref . "cont_bon.bon_num " .
        "LEFT JOIN  " . $tblpref . "article on " . $tblpref . "article.num = " . $tblpref . "cont_bon.article_num " .
        "WHERE " . $tblpref . "bon_comm.num_bon=$num_bon " .
        "AND  " . $tblpref . "article.cat = $CAT_PRESTATION " .
        "GROUP BY taux_tva";

    $totalAndClient4 = processSql(
        $sql,
        $num,
        $fp,
        $date_fact_format,
        $CODE_COMPTABLE_ARTICLE_PRESTATION
    );

    //write total
    $totalAndClient1->total =
      $totalAndClient1->total
    + $totalAndClient2->total
    + $totalAndClient3->total
    + $totalAndClient4->total;
    writeTotal(
        $totalAndClient1,
        $num,
        $fp,
        $date_fact_format);
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
    $num,
    $fp,
    $date_fact_format,
    $codeComptableArticle
) {
    global
    $ZERO_CHAR, 
    $SEP,
    $CODE_COMPTABLE_TVA,
    $CODE_TVA,
    $JOURNAL_VENTE,
    $CLIENT_IGNORE;

    $totalAndClient = new TotalAndClient();
    $totalAndClient->total = 0;
    
    //fwrite(        $fp, $sql . "\n");
    $req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
    while ($data = mysql_fetch_array($req, MYSQL_ASSOC)) {

        $taux_tva = $data['taux_tva'];
        if ($taux_tva === null ||  $taux_tva === '') {
            continue;
        }
        $nom_client = filterNomClient($data['nom'], $CLIENT_IGNORE);
        $totalAndClient->client = $nom_client;
        $total_ht = $data['SUM(tot_art_htva)'];

        fwrite(
            $fp,
            $JOURNAL_VENTE . $SEP .
                $date_fact_format . $SEP .
                $codeComptableArticle[$taux_tva] . $CODE_TVA[$taux_tva] . $SEP .
                $num . $SEP .
                "CLIENT VENTE ARTICLE" . $SEP .
                $SEP .
                $total_ht . $SEP . $SEP
                . "\n"
        );

        $total_tva = $data['SUM(to_tva_art)'];
        fwrite(
            $fp,
            $JOURNAL_VENTE . $SEP .
                $date_fact_format . $SEP .
                $CODE_COMPTABLE_TVA[$taux_tva] . $ZERO_CHAR . $CODE_TVA[$taux_tva] . $SEP .
                $num . $SEP .
                "CLIENT TVA " . $taux_tva . "%" . $SEP .
                $SEP .
                $total_tva . $SEP . $SEP
                . "\n"
        );

        $totalAndClient->total += $total_ht + $total_tva;
    }

    return $totalAndClient;
}

function writeTotal(
    $totalAndClient,
    $num,
    $fp,
    $date_fact_format
)
{
    global
        $SEP,
        $CODE_COMPTABLE_CLIENT,
        $JOURNAL_VENTE;
    fwrite(
        $fp,
        $JOURNAL_VENTE . $SEP .
            $date_fact_format . $SEP .
            $CODE_COMPTABLE_CLIENT . $totalAndClient->client . $SEP .
            $num . $SEP .
            "CLIENT TTC" . $SEP .
            $totalAndClient->total .
            $SEP . $SEP . $SEP
            . "\n"
    );
}

function get_factures($date_from, $date_to, $tblpref)
{
    $sql = "SELECT num, date_fact " .
        "FROM " . $tblpref . "facture " .
        "WHERE " . $tblpref . "facture.date_fact >= '$date_from' " .
        "AND  " . $tblpref . "facture.date_fact < '$date_to' " .
        "ORDER BY num";

    $req = mysql_query($sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
    $factures = array();
    while ($data = mysql_fetch_array($req, MYSQL_ASSOC)) {
        $values = array_values($data);
        array_push($factures, $values[0]);
    }

    return $factures;
}

//get num modifie
$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : "";
$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : "";

//output
$filename = "facture-export-" . $date_from . ".csv";
$fp = fopen('php://output', 'w');

$factures = get_factures($date_from, $date_to, $tblpref);

//HTTP header
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

foreach ($factures as $facture) {
    generate_csv_facture(
        $fp,
        $facture,
        $tblpref
    );
}

fclose($fp);
