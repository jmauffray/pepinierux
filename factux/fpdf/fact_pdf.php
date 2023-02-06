<?php
  /*
   * Factux le facturier libre
   * Copyright (C) 2003-2004 Guy Hendrickx
   * 
   * Licensed under the terms of the GNU  General Public License:
   * 		http://www.opensource.org/licenses/gpl-license.php
   * 
   * For further information visit:
   * 		http://factux.sourceforge.net
   * 
   * File Name: fact_pdf.php
   * 	Fichier generant les factures au format pdf
   * 
   * * * Version:  1.1.5
   * * * * Modified: 23/07/2005
   * 
   * File Authors:
   * 		Guy Hendrickx
   *.
   */

session_cache_limiter('private');
if ($_POST['user']=='adm') { 
  require_once("../include/verif2.php");  
 }else{
  require_once("../include/verif_client.php");
 }
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
define('FPDF_FONTPATH','font/');
require_once('mysql_table.php');
require_once("../include/config/common.php");
require_once("../include/language/$lang.php");
require_once("../include/config/var.php");
require_once("../include/nb.php");
require_once("../include/configav.php");
$client=isset($_POST['client'])?$_POST['client']:"";
$client=array(0=>$client);

$num=isset($_POST['num'])?$_POST['num']:"";
$num=array(0=>$num);
$oneclick=isset($_POST['oneclick'])?$_POST['oneclick']:"";
if($oneclick!=''){
  list($jour, $mois,$annee) = preg_split('/\//', $oneclick, 3);
  $oneclick ="$annee-$mois-$jour";
 }

$nb_li_page = 29;
$devise = utf8_encode(chr(128));
$slogan = stripslashes($slogan);
$entrep_nom= stripslashes($entrep_nom);
$social= stripslashes($social);
$tel= stripslashes($tel);
$tel_portable= stripslashes($tel_portable);
$compte= stripslashes($compte);
$tva_vend= stripslashes($tva_vend);
$reg= stripslashes($reg);
$mail= stripslashes($mail);
$site_web_url= stripslashes($site_web_url);
$siret_num= stripslashes($siret_num);
$code_ape= stripslashes($code_ape);
$code_phyto= stripslashes($code_phyto);
$code_iban= stripslashes($code_iban);
$g=1;
//nouvelle methode
$sql_new ="SELECT * FROM " . $tblpref ."facture WHERE `num` = '$num[0]'";
$req_new = mysql_query($sql_new) or die('Erreur SQL !<br>'.$sql_new.'<br>'.mysql_error());
while($data_new = mysql_fetch_array($req_new))
  {
    $list_num = $data_new['list_num'];
  }

$list_num = unserialize($list_num);
$num_bon = $list_num[0];

$suite_sql=" and " . $tblpref ."bon_comm.num_bon ='$list_num[0]'";

for($m=1; $m<count($list_num); $m++){
  $suite_sql .= " or " . $tblpref ."bon_comm.num_bon ='$list_num[$m]'";

 }
$suite_sql=array(0=>$suite_sql);
if($oneclick!=''){

  $sql2 ="SELECT * FROM " . $tblpref ."facture WHERE `date_fact` = '$oneclick'"; 
  $reqd = mysql_query($sql2) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
  $nb_fact = mysql_num_rows($reqd);
  unset($client);
  unset($num);
  unset($suite_sql);
  $g=0;
  if ($nb_fact=='0') { 
    echo"$lang_fact_mu_err $oneclick";
    exit;  
  }
  $suite_sql=array();
  while($datad = mysql_fetch_array($reqd))
    { 
      $guy=$datad['CLIENT'];

      $num[] = $datad['num'];
      $client[]= $datad['CLIENT'];
      $list_num = $datad['list_num'];
      $list_num = unserialize($list_num);
      $suite_sql[]=" and " . $tblpref ."bon_comm.num_bon ='$list_num[0]'";
      for($m=1; $m<count($list_num); $m++){
	$suite_sql[$g] .= " or " . $tblpref ."bon_comm.num_bon ='$list_num[$m]'";
      }
      $g=$g+1; 
    }

 }
////

////
class PDF extends PDF_MySQL_Table
{
  function Header()
  {
  }
  //debut Js
  var $javascript;
  var $n_js;

  function IncludeJS($script) {
    $this->javascript=$script;
  }

  function _putjavascript() {
    $this->_newobj();
    $this->n_js=$this->n;
    $this->_out('<<');
    $this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R ]');
    $this->_out('>>');
    $this->_out('endobj');
    $this->_newobj();
    $this->_out('<<');
    $this->_out('/S /JavaScript');
    $this->_out('/JS '.$this->_textstring($this->javascript));
    $this->_out('>>');
    $this->_out('endobj');
  }

  function _putresources() {
    parent::_putresources();
    if (!empty($this->javascript)) {
      $this->_putjavascript();
    }
  }

  function _putcatalog() {
    parent::_putcatalog();
    if (isset($this->javascript)) {
      $this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
    }
  }
  function AutoPrint($dialog=false, $nb_impr)
  {
    //Ajoute du JavaScript pour lancer la boîte d'impression ou imprimer immediatement
    $param=($dialog ? 'true' : 'false');
    $script=str_repeat("print($param);",$nb_impr);
		
    $this->IncludeJS($script);
  }
  //fin js
}

$pdf=new PDF();	
$pdf->Open();
$toto="guy";
for ($o=0;$o<$g;$o++)
  {

    //on compte le nombre de lignes
    $sql = "SELECT prix_htva, date, quanti, remise, article, tot_art_htva, to_tva_art, taux_tva, uni, num_bon
 FROM " . $tblpref ."client RIGHT JOIN " . $tblpref ."bon_comm on " . $tblpref ."client.num_client = " . $tblpref ."bon_comm.client_num
  LEFT join " . $tblpref ."cont_bon on " . $tblpref ."bon_comm.num_bon = " . $tblpref ."cont_bon.bon_num  
	LEFT JOIN  " . $tblpref ."article on " . $tblpref ."article.num = " . $tblpref ."cont_bon.article_num
   WHERE " . $tblpref ."client.num_client = '".$client[$o]."'"; 
    // AND " . $tblpref ."bon_comm.date >= '".$debut[$o]."' and " . $tblpref ."bon_comm.date <= '".$fin[$o]."'";
    $sql ="$sql $suite_sql[$o]";

    $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
    $nb_li = mysql_num_rows($req);
    $nb_pa1 = $nb_li /$nb_li_page ;
    $nb_pa = ceil($nb_pa1);
    $nb_li =$nb_pa * $nb_li_page ;

//suite
    $sql = "select payement, acompte, coment, date_echeance, date_depart, DATE_FORMAT(date_fact,'%d/%m/%Y') AS date_2 
from " . $tblpref ."facture where num = $num[$o]";
    $req = mysql_query($sql) or die('Erreur SQL
!<br>'.$sql.'<br>'.mysql_error());
    $data = mysql_fetch_array($req);
    $date_fact = $data[date_2];
    $coment = $data[coment];
    $acompte = $data[acompte];
    $date_depart = $data[date_depart];
    $date_echeance = $data[date_echeance];
    $payement= $data[payement];

    //pour les totaux
    $sql = "SELECT SUM(tot_art_htva), SUM(to_tva_art) FROM " . $tblpref ."client RIGHT JOIN " . $tblpref ."bon_comm on " . $tblpref ."client.num_client = " . $tblpref ."bon_comm.client_num LEFT join " . $tblpref ."cont_bon on " . $tblpref ."bon_comm.num_bon = " . $tblpref ."cont_bon.bon_num  LEFT JOIN  " . $tblpref ."article on " . $tblpref ."article.num = " . $tblpref ."cont_bon.article_num 
WHERE " . $tblpref ."client.num_client = '".$client[$o]."' ";
    //AND " . $tblpref ."bon_comm.date >= '".$debut[$o]."' and " . $tblpref ."bon_comm.date <= '".$fin[$o]."'";
    $sql ="$sql $suite_sql[$o]";
    $req = mysql_query($sql) or die('Erreur SQL
!<br>'.$sql.'<br>'.mysql_error());
    $data = mysql_fetch_array($req);
    $total_htva = $data["SUM(tot_art_htva)"];
    $total_tva = $data["SUM(to_tva_art)"];
    $tot_tva_inc = $tot_tva_inc + $total_htva;

    //pour le nom de client
    $sql1 = "SELECT mail, nom, nom2, rue, ville, cp, num_tva, type, tel, fax FROM " . $tblpref ."client WHERE  num_client = $client[$o]";
    $req = mysql_query($sql1) or die('Erreur SQL !<br>'.$sql1.'<br>'.mysql_error());
    $data = mysql_fetch_array($req);
    $nom = $data['nom'];
    $nom2 = $data['nom2'];
    $rue = $data['rue'];
    $ville = $data['ville'];
    $cp = $data['cp'];
    $num_tva = $data['num_tva'];
    $mail_client = $data['mail'];
    $tel_client = $data['tel'];
    $fax_client = $data['fax'];

    $type = $data['type'];
    $taux_tva='taux_tva';
    if( $type=='particulier' )
      {
	$taux_tva='taux_tva_part';
      }

    for ($i=0;$i<$nb_pa;$i++)
      {
	$nb = $i *$nb_li_page;
	$num_pa = $i;
	$num_pa2 = $num_pa +1;

	$pdf->AddPage();
	//la grande cellule sous le tableau
	$pdf->SetFillColor(255,255,255);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetY(92);
	$pdf->SetX(10);
	$pdf->Cell(187,150,"",1,0,'C',1);

	//premiere celule le numero de bon
	$pdf->SetFont('Arial','B',12);
	$pdf->SetY(10);
	$pdf->SetX(160);
        if($total_tva < 0)
	  {
	    $pdf->Cell(40,6,"$lang_avoir_num_ab $num[$o]",0,0,'L',1);
	  }
	else
	  {
	    $pdf->Cell(40,6,"$lang_fact_num_ab $num[$o]",0,0,'L',1);
	  }
        $nomBis = ereg_replace('[^[:alnum:]]', '_', $nom);
	$file = "facture_numero_$num[$o]_".$nomBis.".pdf";

	//deuxieme cellule les coordonées du CLIENT
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(50);
	$pdf->SetX(105);
	$pdf->MultiCell(65,6,"$nom\n$nom2\n$rue\n$cp  $ville\n",0,L,1);
	//cellule coordonnees client
	$pdf->SetY(50);
	$pdf->SetX(10);
	$pdf->MultiCell(65,6,"TVA N° : $num_tva",0,L,1);

	$pdf->SetY(50);
	$pdf->SetX(165);
	$pdf->MultiCell(65,6,"Tel : $tel_client\nTel : $fax_client",0,L,1);

	//cellule date
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(75);
	$pdf->SetX(60);
	$pdf->MultiCell(45,6,"Date départ : $date_depart\nBon N° $num_bon",0,L,1);

	//le logo
	$pdf->Image("../image/$logo",8,6,53,42);
	$pdf->ln(20);

	//Troisieme cellule le slogan
	$pdf->SetFont('Arial','B',15);
	$pdf->SetY(45);
	$pdf->SetX(10);
	//Troisieme cellule les coordonnées vendeur
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(70);
	$pdf->SetX(10);
	//la date
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(15);
	$pdf->SetX(160);
	$pdf->MultiCell(40,6,"$lang_date: $date_fact",0,L,1);//
	//le cntenu des coordonnées VENDEUR
	$pdf->SetFont('Arial','',8);
	$pdf->SetY(10);
	$pdf->SetX(80);
	$pdf->MultiCell(55,4,"$entrep_nom\n$social\nTél : $tel\nPortable : $tel_portable\n$mail\nTVA N° : $tva_vend\n$siret_num\n$code_ape\n$site_web_url\n",0,L,1);//

	$pdf->Line(10,48,200,48);
	$pdf->ln(50);
	//Le tableau : on définit les colonnes
	$pdf->AddCol('num_ligne',5,"L",'R');
	$pdf->AddCol('num',9,"N°",'R');
	$pdf->AddCol('quanti',6,"Q",'R');
	$pdf->AddCol('article_variete',66,"Désignation",'L');
	$pdf->AddCol('categorie',12,"Série" ,'R');
	$pdf->AddCol('taille',12,"$lang_taille" ,'R');
	$pdf->AddCol('conditionnement',17,"Cond." ,'R');
        $pdf->AddCol($taux_tva,8,"$lang_t_tva",'R');
	$pdf->AddCol('p_u_jour',14,"$lang_prixunitaire",'R');
	$pdf->AddCol('remise',11,"Remise" ,'R');
        //tmp hack!!
	if( $num_bon > 311 )
	  {
	    $pdf->AddCol('p_u_jour_net',13,"Net HT" ,'R');
	  }
	$pdf->AddCol('tot_art_htva',14,"$lang_total_h_tva",'R');
	//$pdf->AddCol('to_tva_art',18,"$lang_tva",'R');

	$prop=array('HeaderColor'=>array(230,230,230),
		    'color1'=>array(255,255,255),
		    'color2'=>array(255,255,255),
		    'align' =>L,
		    'padding'=>1);
        //tmp hack!!
        $conditionnementInTable = $tblpref ."cont_bon.conditionnement";
	if( $num_bon < 59)
	  {
              $conditionnementInTable = $tblpref ."article.conditionnement";
          }
	$sql_table = "SELECT p_u_jour, p_u_jour_net, DATE_FORMAT(date,'%d/%m/%Y') AS date, " . $tblpref ."article.num,
	 quanti, categorie, remise, article, variete,  CONCAT(article, ' ',variete) AS 'article_variete', 
	  phyto, tot_art_htva, to_tva_art, $taux_tva, taille, $conditionnementInTable, uni, num_bon 
FROM " . $tblpref ."client 
RIGHT JOIN " . $tblpref ."bon_comm on " . $tblpref ."client.num_client = " . $tblpref ."bon_comm.client_num 
LEFT join " . $tblpref ."cont_bon on " . $tblpref ."bon_comm.num_bon = " . $tblpref ."cont_bon.bon_num 
LEFT JOIN  " . $tblpref ."article on " . $tblpref ."article.num = " . $tblpref ."cont_bon.article_num 
LEFT JOIN  " . $tblpref ."categorie on " . $tblpref ."article.cat = " . $tblpref ."categorie.id_cat 
WHERE " . $tblpref ."client.num_client = '".$client[$o]."'"; 
	$suite2_sql = "LIMIT $nb, $nb_li_page";
	$sql_table="$sql_table $suite_sql[$o] $suite2_sql";
	$pdf->Table("$sql_table",$prop,$i);
	//deuxieme cellule les coordonnées vendeurs 2
	$pdf->SetFillColor(255,255,255);
	$pdf->SetFont('Arial','',8);
	$pdf->SetY(250);
	$pdf->SetX(5);
	//$pdf->MultiCell(50,4,"$entrep_nom\n$social\n Tél:$tel\n $tva_vend \n$compte \n$reg",0,C,0);
	if($num_pa2 >= $nb_pa)
	  {
	    if ($acompte == '0') { 
  
	      //Quatrieme cellule les enoncés de totaux
	      $pdf->SetFont('Arial','B',10);
	      //$pdf->SetTextColor(255, 0, 0);
	      $pdf->SetY(250);
	      $pdf->SetX(157);
	      $pdf->MultiCell(40,4,avec_virgule ($total_htva)." $devise\n". avec_virgule ($total_tva)." $devise\n",1,R,1);

	      $pdf->SetY(258);
	      $pdf->SetX(157);
	      $pdf->SetTextColor(0, 0, 0);
	      $pdf->MultiCell(40,4,avec_virgule ($total_htva + $total_tva)." $devise",1,R,1);
	      //Cinquieme cellule les totaux
	      $pdf->SetFont('Arial','B',10);
	      $pdf->SetTextColor(0, 0, 0);
	      $pdf->SetY(250);
	      $pdf->SetX(117);
	      $pdf->MultiCell(40,4,"$lang_total_h_tva: \n $lang_tot_tva: \n $lang_tot_ttc:",1,R,1);
	    }else{
	      //si un acompte est present

	      //Quatrieme cellule les enoncés de totaux
	      $pdf->SetFont('Arial','B',10);
	      $pdf->SetY(250);
	      $pdf->SetX(157);
	      $pdf->MultiCell(40,4,avec_virgule ($total_htva)." $devise\n".
			      avec_virgule ($total_tva)." $devise\n".
			      avec_virgule ($total_htva + $total_tva)." $devise\n".
			      avec_virgule ($acompte)." $devise\n"
			      ,1,R,1);
	      $pdf->SetFont('Arial','B',10);
	      $pdf->SetTextColor(0, 0, 0);
	      $pdf->SetY(266);
	      $pdf->SetX(157);
	      $pdf->MultiCell(40,4, avec_virgule ($total_htva + $total_tva - $acompte)." $devise\n",1,R,1);
	      //Cinquieme cellule les totaux
	      $pdf->SetFont('Arial','B',10);
	      $pdf->SetTextColor(0, 0, 0);
	      $pdf->SetY(250);
	      $pdf->SetX(117);
	      $pdf->MultiCell(40,4,"$lang_total_h_tva: \n $lang_tot_tva: \n $lang_tot_ttc: \n $lang_acompte: \n $lang_rest_pay: ",1,R,1);
	    }
	    //la ventillation de la tva
	    $pdf->SetFont('Arial','B',8);
	    $pdf->SetY(250);
	    $pdf->SetX(10);
	    $pdf->MultiCell(20,4,"$lang_t_tva",1,C,1);

	    $pdf->SetFont('Arial','B',8);
	    $pdf->SetY(250);
	    $pdf->SetX(30);
	    $pdf->MultiCell(20,4,"$lang_montant",1,C,1);

	    $pdf->SetFont('Arial','B',8);
	    $pdf->SetY(250);
	    $pdf->SetX(50);
	    $pdf->MultiCell(25,4,"$lang_ba_imp",1,C,1);


	    $sql2="SELECT SUM(to_tva_art), SUM(tot_art_htva),$taux_tva
			FROM " . $tblpref ."client
			RIGHT JOIN " . $tblpref ."bon_comm on " . $tblpref ."client.num_client = " . $tblpref ."bon_comm.client_num 
			LEFT join " . $tblpref ."cont_bon on " . $tblpref ."bon_comm.num_bon = " . $tblpref ."cont_bon.bon_num 
			LEFT JOIN  " . $tblpref ."article on " . $tblpref ."article.num = " . $tblpref ."cont_bon.article_num 
			WHERE " . $tblpref ."client.num_client = '".$client[$o]."'"; 
	    $suite3_sql=" GROUP BY $taux_tva";
	    $sql2="$sql2 $suite_sql[$o] $suite3_sql";
	    $pdf->AddCol($taux_tva,20,'taux tva','L');
	    $pdf->AddCol('SUM(to_tva_art)',20,'moontant tva','L');
	    $pdf->AddCol('SUM(tot_art_htva)',25,"$lang_ba_imp",'L');
	    $prop=array('color1'=>array(255,255,255),
			'color2'=>array(255,255,255),
			'padding'=>2,
			'entete'=>0,
			'align' =>L);
	    $pdf->Table("$sql2",$prop);

            //NEGOCE : categorie id = 7 dans ce cas
	    $sql2_negoce="SELECT SUM(tot_art_htva)
			FROM " . $tblpref ."client
			RIGHT JOIN " . $tblpref ."bon_comm on " . $tblpref ."client.num_client = " . $tblpref ."bon_comm.client_num 
			LEFT join " . $tblpref ."cont_bon on " . $tblpref ."bon_comm.num_bon = " . $tblpref ."cont_bon.bon_num 
			LEFT JOIN  " . $tblpref ."article on " . $tblpref ."article.num = " . $tblpref ."cont_bon.article_num 
			WHERE " . $tblpref ."bon_comm.num_bon = '".$num_bon."' AND " . $tblpref ."article.cat = '7'"; 
            $suite3_sql_negoce=" GROUP BY $taux_tva";
	    $sql2_negoce="$sql2_negoce $suite3_sql_negoce";
	    $resu_negoce = mysql_query( $sql2_negoce ) or die('Erreur SQL !<br>'.$sql2_negoce.'<br>'.mysql_error());
            $data_negoce_prix = 0;
            $rowsfound = mysql_num_rows($resu_negoce);
            while($row = mysql_fetch_array($resu_negoce))
            {
                $data_negoce_prix += $row[0];
            }

	    //fin ventillation
	    //pour les commentaire
	    $pdf->SetFont('Arial','',10);
	    $pdf->SetY(245);
	    $pdf->SetX(10);
	    $pdf->MultiCell(190,4,"$coment",0,C,0);

	    if( $type != 'particulier' )
	      {
		//ligne d'escompte pour pro
		$pdf->SetFont('Arial','',10);
		$pdf->SetY(262);
		$pdf->SetX(10);
		$pdf->MultiCell(190,4,"Négoce HT : ". $data_negoce_prix.$devise,0,C,0);
	      }
            else
              {
		//ligne negoce pour particulier
		$pdf->SetFont('Arial','',10);
		$pdf->SetY(262);
		$pdf->SetX(10);
		$pdf->MultiCell(190,4,"Négoce HT : ". $data_negoce_prix .$devise, 0,C,0);
              }

	    //date échéance
            if ($payement=='non') { 
	        $pdf->SetY(265);
	        $pdf->SetX(10);
	        $pdf->MultiCell(65,4,"Echéance le $date_echeance",0,C,0);
	    }

	  }
	if ($payement!='non') { 
	  $pdf->SetFont('Times','bu',12);
	  $pdf->SetY(265);
	  $pdf->SetX(140);
	  $pdf->MultiCell(60,4,"$lang_po_acquis",0,C,0);  
	}
	$pdf->Line(10,277,200,277);
	//la derniere cellule conditions de facturation
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(268);
	$pdf->SetX(30);
	$pdf->SetY(268);
	$annee_fact = substr ($date_fact,6,4);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(272);
	$pdf->SetX(30);
	$pdf->MultiCell(160,4,"$lang_page $num_pa2 / $nb_pa\n",0,R,0);

	$pdf->SetFont('Arial','',8);
	$pdf->SetY(272);
	$pdf->SetX(10);
	$pdf->MultiCell(160,4,"$code_iban\n",0,L,0);

      }
    if($_POST['mail'] =='y'){
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',10);
      $pdf->SetY(10);
      $pdf->SetX(30);
      $pdf->MultiCell(160,4,"Conditions génerales de vente\n",0,C,0);
      $pdf->SetY(70);
      $pdf->SetX(10);
      $pdf->MultiCell(160,4,"$lang_condi_ven",0,C,0);
    }
  }
if($autoprint=='y' and $_POST['mail']!='y' and $_POST['user']=='adm'){
  $pdf->AutoPrint(false, $nbr_impr);
 }
$pdf->Output($file); 

if ($_POST['mail']=='y') { 	 
  $to = "$mail_client";
  $sujet = "Nouvelle facture de $entrep_nom";
  $message = "Une nouvelle facture vous a étée adressée par  $entrep_nom . \nVous la trouverez en piece jointe de mail\n Salutations distinguées \n $entrep_nom";
  $fichier = "$file";
  $typemime = "pdf";
  $nom = "$file";
  $reply = "$mail";
  $from = "$mail";
  require "../include/CMailFile.php";
  $newmail = new CMailFile("$sujet","$to","$from","$message","$fichier","application/pdf");
  $newmail->sendfile();

  echo "<HTML><SCRIPT>document.location='../lister_factures.php';</SCRIPT></HTML>";
  
 }else{

  echo "<HTML><SCRIPT>document.location='$file';</SCRIPT></HTML>";
 }

?> 
