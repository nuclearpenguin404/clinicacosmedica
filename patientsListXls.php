<?php

/*$dbhost='127.0.0.1';
$database='clinicacosmedica';
$dbuser='clinicacosmedica';
$dbpassword='L5ncAvet8uwbf96X';*/

include 'dbConfig.php';


$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 


$Q="SELECT * FROM patients ORDER BY lastName";
$R=mysql_query($Q);
$rows=mysql_num_rows($R);

$fileName="PatientsList.xls";

header('Content-type: application/ms-excel;charset=UTF-8');
//header('Content-type: application/ms-excel');
//header('Content-Disposition: attachment; filename='.$fileName.'.xls');
header('Content-Disposition: attachment; filename='.$fileName);

print "<html><body>";
print "<table>";
print "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

print "<tr><th>First Name</th><th>Last Name</th><th>Cell Phone Number</th><th>Home Phone Number</th><th>E-mail</th><th>Date of Birth</th><th>Address</th></tr>";

for($j=0;$j<$rows;$j++)
{
	
	list($id,$firstName,$lastName,$cellPhone,$homePhone,$eMail,$dateOfBirth,$address)=mysql_fetch_row($R);
	
	print "<tr><td>$firstName</td><td>$lastName</td><td>$cellPhone</td><td>$homePhone</td><td>$eMail</td><td>$dateOfBirth</td><td>$address</td></tr>";
}
print "</table>";


print "</body></html>";

?>
