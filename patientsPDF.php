<?php

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

include 'dbConfig.php';


$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 


$Q="SELECT * FROM doctors";
$R=mysql_query($Q);


list($id,$firstName,$lastName,$cellPhone)=mysql_fetch_row($R);
$patientHeader="List of Patients";

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Cosmedica');
$pdf->SetTitle('Cosmedica');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 009', PDF_HEADER_STRING);
//$pdf->SetHeaderData('',0, 'Patient: John Smith', 'http://clinicacosmedica.com');
$pdf->SetHeaderData('',0, $patientHeader, 'http://clinicacosmedica.com');


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// -------------------------------------------------------------------

// add a page
$pdf->AddPage('P','A4');

// set JPEG quality
//$pdf->setJPEGQuality(90);



$str = array();
array_push($str,"<h2>List of Patients</h2>");


$Q="SELECT * FROM patients";
$R=mysql_query($Q);
$patientsNmb=mysql_num_rows($R);

if($patientsNmb>0)
{

	
	array_push($str,"<table border=\"1\" cellpadding=\"4\" cellspacing=\"0\" style=\"font-size:80%\">");
	array_push($str,"<tr><td style=\"width:15%;\"><strong>First Name</strong></td>");
	array_push($str,"<td style=\"width:15%;\"><strong>Last Name</strong></td>");
	array_push($str,"<td style=\"width:15%;\"><strong>Cell Phone</strong></td>");
	array_push($str,"<td style=\"width:15%;\"><strong>Home Phone</strong></td>");
	array_push($str,"<td style=\"width:15%;\"><strong>E-mail</strong></td>");
	array_push($str,"<td style=\"width:15%;\"><strong>Date of Birth</strong></td>");
	array_push($str,"<td style=\"width:15%;\"><strong>Address</strong></td></tr>");
	

	

	for($i=0;$i<$patientsNmb;$i++)
	{
		
		list($id,$firstName,$lastName,$cellPhone,$homePhone,$eMail,$dateOfBirth,$address)=mysql_fetch_row($R);
					
		array_push($str,"<tr><td>$firstName</td>");
		array_push($str,"<td>$lastName</td>");
		array_push($str,"<td>$cellPhone</td>");
		array_push($str,"<td>$homePhone</td>");
		array_push($str,"<td>$eMail</td>");
		array_push($str,"<td>$dateOfBirth</td>");
		array_push($str,"<td>$address</td></tr>");		
	}

	array_push($str,"</table>");
}



$html=join("\n",$str);
$pdf->writeHTML($html, true, false, true, false, '');


$pdf->Output('PatientsList.pdf', 'I');


?>