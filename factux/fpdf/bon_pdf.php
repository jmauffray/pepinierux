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

////////////////////////////////INC////////////////////////////
$nb_li_page = 29;
$num_bon=isset($_POST['num_bon'])?$_POST['num_bon']:"";
$nom=isset($_POST['nom'])?$_POST['nom']:"";
$sql = "SELECT " . $tblpref ."cont_bon.num, quanti, uni, article, prix_htva, tot_art_htva FROM " . $tblpref ."cont_bon RIGHT JOIN " . $tblpref ."article on " . $tblpref ."cont_bon.article_num = " . $tblpref ."article.num WHERE  bon_num = $num_bon";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
$nb_li = mysql_num_rows($req);
$nb_pa1 = $nb_li /$nb_li_page ;
$nb_pa = ceil($nb_pa1);
//$nb_li =$nb_pa * $nb_li_page ;
//pour savoir si phyto
$sql = "SELECT " . $tblpref ."cont_bon.num, quanti, uni, article, prix_htva, tot_art_htva FROM " . $tblpref ."cont_bon RIGHT JOIN " . $tblpref ."article on " . $tblpref ."cont_bon.article_num = " . $tblpref ."article.num WHERE  bon_num = $num_bon AND phyto <> ''";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
$isPhyto = mysql_num_rows($req);
//pour la date
$sql = "select coment, tot_htva, tot_tva, DATE_FORMAT(date,'%d/%m/%Y') AS date_2 from " . $tblpref ."bon_comm where num_bon = $num_bon";
$req = mysql_query($sql) or die('Erreur SQL
!<br>'.$sql.'<br>'.mysql_error());
$data = mysql_fetch_array($req);
$date_bon = $data[date_2];
$total_htva = $data[tot_htva];
$total_tva = $data[tot_tva];
$tot_tva_inc = $total_htva + $total_tva ;
$coment = $data[coment];
//pour le nom de client
$sql1 = "SELECT num_client, mail, nom, nom2, rue, ville, cp, num_tva, type FROM " . $tblpref ."client RIGHT JOIN " . $tblpref ."bon_comm on client_num = num_client WHERE  num_bon = $num_bon";
$req = mysql_query($sql1) or die('Erreur SQL !<br>'.$sql1.'<br>'.mysql_error());
$data = mysql_fetch_array($req);
$nom = $data['nom'];
$nom2 = $data['nom2'];
$rue = $data['rue'];
$ville = $data['ville'];
$cp = $data['cp'];
$num_tva = $data['num_tva'];
$type = $data['type'];
$mail_client = $data['mail'];
$num_client = $data['num_client'];

$taux_tva='taux_tva';
//unused p_u_jour used si le prix a varié depuis le bon de commande
//$prix_htva='prix_htva';
if( $type=='particulier' )
  {
    $taux_tva='taux_tva_part';
    //unused $prix_htva='prix_htva_part';
  }
////////////////////////////////INC////////////////////////////

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
$g=1;

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
//for ($o=0;$o<$g;$o++)
//  {

for ($i=0;$i<$nb_pa;$i++)
  {
    $nb = $i *$nb_li_page;
    //on compte le nombre de lignes
    $sql = "SELECT " . $tblpref ."cont_bon.num, num_lot, quanti, uni, remise, volume_pot, article, variete, phyto, $taux_tva, prix_htva, p_u_jour, p_u_jour_net, tot_art_htva FROM " . $tblpref ."cont_bon RIGHT JOIN " . $tblpref ."article on " . $tblpref ."cont_bon.article_num = " . $tblpref ."article.num WHERE  bon_num = $num_bon LIMIT $nb, $nb_li_page";
    $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

    //pour les totaux
    $sql = "SELECT SUM(tot_art_htva), SUM(to_tva_art) FROM " . $tblpref ."client RIGHT JOIN " . $tblpref ."bon_comm on " . $tblpref ."client.num_client = " . $tblpref ."bon_comm.client_num LEFT join " . $tblpref ."cont_bon on " . $tblpref ."bon_comm.num_bon = " . $tblpref ."cont_bon.bon_num  LEFT JOIN  " . $tblpref ."article on " . $tblpref ."article.num = " . $tblpref ."cont_bon.article_num 
WHERE " . $tblpref ."bon_comm.num_bon = '".$num_bon."' ";
    //AND " . $tblpref ."bon_comm.date >= '".$debut[$o]."' and " . $tblpref ."bon_comm.date <= '".$fin[$o]."'";
    //    $sql ="$sql $suite_sql[$o]";
    $req = mysql_query($sql) or die('Erreur SQL
!<br>'.$sql.'<br>'.mysql_error());
    $data = mysql_fetch_array($req);
    $total_htva = $data["SUM(tot_art_htva)"];
    $total_tva = $data["SUM(to_tva_art)"];
    $tot_tva_inc = $tot_tva_inc + $total_htva;

    //pour le nom de client
    $sql1 = "SELECT mail, nom, nom2, rue, ville, cp, num_tva, type, tel, fax FROM " . $tblpref ."client WHERE  num_client = $num_client";
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
	$pdf->SetX(140);
	$pdf->Cell(40,6,"$lang_num_bon_ab N° $num_bon",0,0,'L',1);
        $nomBis = ereg_replace('[^[:alnum:]]', '_', $nom);
	$file = "bon_numero_".$num_bon."_".$nomBis.".pdf";

	//deuxieme cellule les coordonées du CLIENT
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(50);
	$pdf->SetX(105);
	$pdf->MultiCell(65,6,"$nom\n$nom2\n$rue\n$cp  $ville\n",0,L,1);
	//cellule coordonnees client
	$pdf->SetY(50);
	$pdf->SetX(10);
	$pdf->MultiCell(65,6,"TVA N° : $num_tva\nTel : $tel_client\nTel : $fax_client",0,L,1);

	//le logo
	$pdf->Image("../image/$logo",8,6,53,42);
	$pdf->ln(20);

	//Troisieme cellule le slogan
	$pdf->SetFont('Arial','B',15);
	$pdf->SetY(45);
	$pdf->SetX(10);
	//$pdf->MultiCell(71,4,"$slogan",0,C,0);
	//Troisieme cellule les coordonnées vendeur
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(70);
	$pdf->SetX(10);
	//$pdf->MultiCell(40,4,"$lang_dev_pdf_soc",1,R,1);
	//la date
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(15);
	$pdf->SetX(140);
	$pdf->MultiCell(40,6,"$lang_date: $date_bon",0,L,1);//
	//le cntenu des coordonnées VENDEUR
	$pdf->SetFont('Arial','',8);
	$pdf->SetY(10);
	$pdf->SetX(80);
	$pdf->MultiCell(55,4,"$entrep_nom\n$social\nTél/Tel : $tel\nPortable : $tel_portable\n$mail\nTVA N° : $tva_vend\n$siret_num\n$code_ape\n$site_web_url\n",0,L,1);//
	//phyto
	if( $isPhyto > 0 )
	  {
	    //le logo
	    $pdf->Image("../image/passeport-phyto.jpg",10,75,50,13);
	  }
	$pdf->Line(10,48,200,48);
	$pdf->ln(50);
	//Le tableau : on définit les colonnes
	//$pdf->AddCol('num_bon',7,"$lang_num_bon_ab",'L');
	$pdf->AddCol('num_ligne',5,"L",'R');
	$pdf->AddCol('article_num',9,"N°",'R');
	//$pdf->AddCol('date',15,"$lang_date",'C');
	$pdf->AddCol('quanti',6,"Q",'R');
	$pdf->AddCol('article',24,"$lang_articles",'L');
	$pdf->AddCol('variete',39,"$lang_variete",'L');
	$pdf->AddCol('phyto',3," ",'L');
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
	$sql_table = "SELECT " . $tblpref ."cont_bon.num, num_lot, article_num, quanti, uni, categorie, remise, volume_pot, article, variete, phyto, taille, $conditionnementInTable, $taux_tva, prix_htva, p_u_jour, p_u_jour_net, tot_art_htva FROM " . $tblpref ."cont_bon 
RIGHT JOIN " . $tblpref ."article on " . $tblpref ."cont_bon.article_num = " . $tblpref ."article.num 
LEFT JOIN  " . $tblpref ."categorie on " . $tblpref ."article.cat = " . $tblpref ."categorie.id_cat 
WHERE  bon_num = $num_bon LIMIT $nb, $nb_li_page";
	//	$suite2_sql = "LIMIT $nb, $nb_li_page";
	$sql_table="$sql_table $suite_sql[$o] $suite2_sql";
	$pdf->Table("$sql_table",$prop,$i);
	//deuxieme cellule les coordonnées vendeurs 2
	$pdf->SetFillColor(255,255,255);
	$pdf->SetFont('Arial','',8);
	$pdf->SetY(240);
	$pdf->SetX(5);
	//$pdf->MultiCell(50,4,"$entrep_nom\n$social\n Tél:$tel\n $tva_vend \n$compte \n$reg",0,C,0);
	if($num_pa2 >= $nb_pa)
	  {
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
			WHERE " . $tblpref ."bon_comm.num_bon = '".$num_bon."'"; 
	    $suite3_sql=" GROUP BY $taux_tva";
	    $sql2="$sql2 $suite_sql[$o] $suite3_sql";
	    ///echo"$sql2<br>";			
	    ////$resu = mysql_query( $sql2 ) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());
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
		$pdf->MultiCell(190,4,"Escompte de 2% pour réglement sous 8 jours après enlèvement ou expédition. Négoce HT : ". $data_negoce_prix.$devise,0,C,0);
	      }
            else
              {
		//ligne negoce pour particulier
		$pdf->SetFont('Arial','',10);
		$pdf->SetY(262);
		$pdf->SetX(10);
		$pdf->MultiCell(190,4,"Négoce HT : ". $data_negoce_prix .$devise, 0,C,0);
              }
	  }
	
	$pdf->Line(10,267,200,267);
	//la derniere cellule conditions de facturation
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(258);
	$pdf->SetX(30);
	$pdf->SetY(268);
	$annee_fact = substr ($date_fact,6,4);
	//$pdf->MultiCell(0,4,"$lang_factpdf_penalites_conditions",0,C,0);
        //$pdf->MultiCell(0,4,"Livraison sur RDV de 8H00 ŕ 12H00",0,C,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(270);
	$pdf->SetX(30);
	$pdf->MultiCell(160,4,"$lang_page $num_pa2 / $nb_pa\n",0,C,0);

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
