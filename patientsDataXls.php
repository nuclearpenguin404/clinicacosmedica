<?php


include 'dbConfig.php';

$patientId = $_GET['id']; 


$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 


$Q="SELECT * FROM patients WHERE id='$patientId'";
$R=mysql_query($Q);


list($id,$firstName,$lastName,$cellPhone,$homePhone,$eMail,$dateOfBirth,$address)=mysql_fetch_row($R);

$fileName="$lastName$firstName.xls";

header('Content-type: application/ms-excel;charset=UTF-8');
//header('Content-type: application/ms-excel');
//header('Content-Disposition: attachment; filename='.$fileName.'.xls');
header('Content-Disposition: attachment; filename='.$fileName);

print "<html>";

print "
<head>
<!--[if gte mso 9]><xml>
<x:ExcelWorkbook>
<x:ExcelWorksheets>
<x:ExcelWorksheet>
<x:Name>Application List</x:Name>
<x:WorksheetOptions>
<x:Print>
</x:Print>
</x:WorksheetOptions>
</x:ExcelWorksheet>
</x:ExcelWorksheets>
</x:ExcelWorkbook> 
</xml>
<![endif]--> 
</head>";



print "<body>";
print "<table>";
print "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

print "<tr><th>First Name</th><th>Last Name</th><th>Cell Phone Number</th><th>Home Phone Number</th><th>E-mail</th><th>Date of Birth</th><th>Address</th></tr>";
print "<tr><td>$firstName</td><td>$lastName</td><td>$cellPhone</td><td>$homePhone</td><td>$eMail</td><td>$dateOfBirth</td><td>$address</td></tr>";
print "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

print "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";



print "<tr><th colspan='7' align='left'>Treatments</th></tr>";
print "<tr><td></td><th>Session Number</th><th>Date</th><th>Therapist</th><th>Auxillary Therapist</th><th>Next Session Date</th><th>Kind of Treatment</th></tr>";


	$Q="SELECT * FROM treatments WHERE patientId='$patientId' ORDER BY id";
	$R=mysql_query($Q);
	$treatmentsNmb=mysql_num_rows($R);

	for($i=0;$i<$treatmentsNmb;$i++)
	{
		print "<tr>";
		list($sessionId,$patientId,$date,$kindOfTreatment,$therapistId,$auxiliaryTherapistId,$sessionNr,$nextSessionDate)=mysql_fetch_row($R);
		//print "Session Number\Date\tKind of Treatment\tTherapist\tAuxillary Therapist\tNext Session Date\n";
		print "<td></td>";
		print "<td>$sessionNr</td>";
		print "<td>$date</td>";

		$Q2="SELECT firstName,lastName FROM doctors WHERE id='$therapistId'";
		$R2=mysql_query($Q2);
		list($thFirstName,$thLastName)=mysql_fetch_row($R2);
		$thLastFirst="$thLastName $thFirstName";
		
		print "<td>$thLastFirst</td>";

		if($therapistId==$auxiliaryTherapistId)
		{		
			print "<td>$thLastFirst</td>";
		}
		else
		{
			$Q2="SELECT lastName,firstName FROM doctors WHERE id='$auxiliaryTherapistId'";
			$R2=mysql_query($Q2);
			list($thLastName,$thFirstName)=mysql_fetch_row($R2);
			$thLastFirst="$thLastName $thFirstName";
			print "<td>$thLastFirst</td>";
		}

		print "<td>$nextSessionDate</td>";
		//print "<td>$kindOfTreatment</td>";
		print "<td><pre>$kindOfTreatment</pre></td>";
		print "</tr>";
	}


print "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

print "<tr><th colspan='7' align='left'>Progress</th></tr>";
print "<tr><th>Measurement Number</th><th>Date</th><th>Weight, kg</th><th>Waist Measure, cm</th><th>Hip Measure, cm</th><th>Arm Measure, cm</th><th></th></tr>";


$Q="SELECT * FROM measurements WHERE patientId='$patientId' ORDER BY id";
$R=mysql_query($Q);
$measurementNmb=mysql_num_rows($R);


for($i=0;$i<$measurementNmb;$i++)
{
	print "<tr>";

	list($measurementId,$patientId,$measurementNr,$date,$weightMeasure,$waistMeasure,$hipMeasure,$armMeasure)=mysql_fetch_row($R);
	
	print "<td>$measurementNr</td>";
	print "<td>$date</td>";
	print "<td>$weightMeasure</td>";
	print "<td>$waistMeasure</td>";
	print "<td>$hipMeasure</td>";
	print "<td>$armMeasure</td>";
	print "<td></td>";
	print "</tr>";
}
print "</table>";
print "</body></html>";



?>
