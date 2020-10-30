<?php

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

include 'dbConfig.php';

$patientId = $_POST['patientId'];
//echo $patientId; 

$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 


$Q="SELECT * FROM patients WHERE id='$patientId'";
$R=mysql_query($Q);


list($id,$firstName,$lastName,$cellPhone,$homePhone,$eMail,$dateOfBirth,$address)=mysql_fetch_row($R);
$parientHeader="Patient: $firstName $lastName";

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
$pdf->SetHeaderData('',0, $parientHeader, 'http://clinicacosmedica.com');


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
$pdf->AddPage();

// set JPEG quality
//$pdf->setJPEGQuality(90);



$str = array();
array_push($str,"<h2>Personal Data</h2>");

array_push($str,"<table border=\"1\" cellpadding=\"4\" cellspacing=\"0\" style=\"font-size:80%\">");
array_push($str,"<tr><td style=\"width:10%;\"><strong>First Name</strong></td><td>$firstName</td></tr>");
array_push($str,"<tr><td style=\"width:10%;\"><strong>Last Name</strong></td><td>$lastName</td></tr>");
array_push($str,"<tr><td style=\"width:10%;\"><strong>Cell Phone</strong></td><td>$cellPhone</td></tr>");
array_push($str,"<tr><td style=\"width:10%;\"><strong>Home Phone</strong></td><td>$homePhone</td></tr>");
array_push($str,"<tr><td style=\"width:10%;\"><strong>E-mail</strong></td><td>$eMail</td></tr>");
array_push($str,"<tr><td style=\"width:10%;\"><strong>Date of Birth</strong></td><td>$dateOfBirth</td></tr>");
array_push($str,"<tr><td style=\"width:10%;\"><strong>Address</strong></td><td>$address</td></tr>");
array_push($str,"</table>");



$Q="SELECT * FROM treatments WHERE patientId='$patientId' ORDER BY id";
$R=mysql_query($Q);
$treatmentsNmb=mysql_num_rows($R);

if($treatmentsNmb>0)
{

	array_push($str,"<p>&nbsp;</p>");


	array_push($str,"<h2>Treatments</h2>");
	array_push($str,"<table border=\"1\" cellpadding=\"4\" cellspacing=\"0\" style=\"font-size:80%\">");
	array_push($str,"<tr><td style=\"width:5%;\"><strong>Session Nr</strong></td>");
	array_push($str,"<td style=\"width:8%;\"><strong>Date</strong></td>");
	array_push($str,"<td style=\"width:15%;\"><strong>Therapist</strong></td>");
	array_push($str,"<td style=\"width:15%;\"><strong>Auxillary Therapist</strong></td>");
	array_push($str,"<td style=\"width:8%;\"><strong>Next Session Date</strong></td>");
	array_push($str,"<td style=\"width:46%;\"><strong>Kind of Treatment</strong></td></tr>");

	

	for($i=0;$i<$treatmentsNmb;$i++)
	{
		array_push($str,"<tr>");
		list($sessionId,$patientId,$date,$kindOfTreatment,$therapistId,$auxiliaryTherapistId,$sessionNr,$nextSessionDate)=mysql_fetch_row($R);
		//print "Session Number\Date\tKind of Treatment\tTherapist\tAuxillary Therapist\tNext Session Date\n";
			
		array_push($str,"<td>$sessionNr</td>");
		array_push($str,"<td>$date</td>");

		$Q2="SELECT firstName,lastName FROM doctors WHERE id='$therapistId'";
		$R2=mysql_query($Q2);
		list($thFirstName,$thLastName)=mysql_fetch_row($R2);
		$thLastFirst="$thLastName $thFirstName";
					
		array_push($str,"<td>$thLastFirst</td>");

		if($therapistId==$auxiliaryTherapistId)
		{		
			array_push($str,"<td>$thLastFirst</td>");
		}
		else
		{
			$Q2="SELECT lastName,firstName FROM doctors WHERE id='$auxiliaryTherapistId'";
			$R2=mysql_query($Q2);
			list($thLastName,$thFirstName)=mysql_fetch_row($R2);
			$thLastFirst="$thLastName $thFirstName";
			array_push($str,"<td>$thLastFirst</td>");
		}

		array_push($str,"<td>$nextSessionDate</td>");
		//array_push($str,"<td><pre>$kindOfTreatment</pre></td>");
		array_push($str,"<td>$kindOfTreatment</td>");
		array_push($str,"</tr>");
	}

	array_push($str,"</table>");
}


$Q="SELECT * FROM measurements WHERE patientId='$patientId' ORDER BY id";
$R=mysql_query($Q);
$measurementNmb=mysql_num_rows($R);

if($measurementNmb>0)
{

	array_push($str,"<p>&nbsp;</p>");
	array_push($str,"<h2>Progress</h2>");
	
	array_push($str,"<table border=\"1\" cellpadding=\"4\" cellspacing=\"0\" style=\"font-size:80%\">");
	array_push($str,"<tr><td style=\"width:8%;\"><strong>Measurement Nr</strong></td>");
	array_push($str,"<td style=\"width:8%;\"><strong>Date</strong></td>");
	array_push($str,"<td style=\"width:12%;\"><strong>Weight, kg</strong></td>");
	array_push($str,"<td style=\"width:12%;\"><strong>Waist Measure, cm</strong></td>");
	array_push($str,"<td style=\"width:12%;\"><strong>Hip Measure, cm</strong></td>");
	array_push($str,"<td style=\"width:12%;\"><strong>Arm Measure, cm</strong></td></tr>");



	for($i=0;$i<$measurementNmb;$i++)
	{
		list($measurementId,$patientId,$measurementNr,$date,$weightMeasure,$waistMeasure,$hipMeasure,$armMeasure)=mysql_fetch_row($R);
		
		array_push($str,"<tr><td>$measurementNr</td><td>$date</td><td>$weightMeasure</td><td>$waistMeasure</td><td>$hipMeasure</td><td>$armMeasure</td></tr>");	
	}

	array_push($str,"</table>");
}



$html=join("\n",$str);
$pdf->writeHTML($html, true, false, true, false, '');





$weightIMGPOST = isset($_POST['weightIMGData']) ? $_POST['weightIMGData'] :'';
$waistIMGPOST = isset($_POST['waistIMGData']) ? $_POST['waistIMGData'] :'';
$hipIMGPOST = isset($_POST['hipIMGData']) ? $_POST['hipIMGData'] :'';
$armIMGPOST = isset($_POST['armIMGData']) ? $_POST['armIMGData'] :'';

//$pdf->AddPage();




if($weightIMGPOST||$waistIMGPOST||$hipIMGPOST||$armIMGPOST)
{	
	//$pdf->AddPage();
	$weightIMGData=str_replace('data:image/png;base64','',$weightIMGPOST);
	//print $weightIMGData;
	$weightIMG = base64_decode($weightIMGData);
	$pdf->Image('@'.$weightIMG);


	$pdf->AddPage();
	$waistIMGData=str_replace('data:image/png;base64','',$waistIMGPOST);
	//print $weightIMGData;
	$waistIMG = base64_decode($waistIMGData);
	$pdf->Image('@'.$waistIMG);

	$pdf->AddPage();
	$hipIMGData=str_replace('data:image/png;base64','',$hipIMGPOST);
	//print $weightIMGData;
	$hipIMG = base64_decode($hipIMGData);
	$pdf->Image('@'.$hipIMG);
	

	$pdf->AddPage();
	$armIMGData=str_replace('data:image/png;base64','',$armIMGPOST);
	//print $weightIMGData;
	$armIMG = base64_decode($armIMGData);
	$pdf->Image('@'.$armIMG);
}




$pdf->Output('PatientsData.pdf', 'I');


?>