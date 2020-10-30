<?php

/*$dbhost='127.0.0.1';
$database='clinicacosmedica';
$dbuser='clinicacosmedica';
$dbpassword='L5ncAvet8uwbf96X';*/

include 'dbConfig.php';


$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 




$fileName="DoctorsList.xls";

header('Content-type: application/ms-excel;charset=UTF-8');
//header('Content-type: application/ms-excel');
//header('Content-Disposition: attachment; filename='.$fileName.'.xls');
header('Content-Disposition: attachment; filename='.$fileName);

print "<html><body>";

$Q="SELECT * FROM doctors WHERE isActive='1' ORDER BY lastName";
$R=mysql_query($Q);
$rows=mysql_num_rows($R);

print "<table>";
print "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

print "<tr><th colspan=\"6\">Active Doctors</th></tr>";

print "<tr><th>First Name</th><th>Last Name</th><th>Cell Phone Number</th><th>Home Phone Number</th><th>E-mail</th><th>Address</th></tr>";

for($j=0;$j<$rows;$j++)
{
	
	list($id,$isActive,$firstName,$lastName,$cellPhone,$homePhone,$eMail,$address)=mysql_fetch_row($R);
	
	print "<tr><td>$firstName</td><td>$lastName</td><td>$cellPhone</td><td>$homePhone</td><td>$eMail</td><td>$address</td></tr>";
}


print "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

$Q="SELECT * FROM doctors WHERE isActive='0' ORDER BY lastName";
$R=mysql_query($Q);
$rows=mysql_num_rows($R);


print "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

print "<tr><th colspan=\"6\">Inactive Doctors</th></tr>";

print "<tr><th>First Name</th><th>Last Name</th><th>Cell Phone Number</th><th>Home Phone Number</th><th>E-mail</th><th>Address</th></tr>";

for($j=0;$j<$rows;$j++)
{
	
	list($id,$isActive,$firstName,$lastName,$cellPhone,$homePhone,$eMail,$address)=mysql_fetch_row($R);
	
	print "<tr><td>$firstName</td><td>$lastName</td><td>$cellPhone</td><td>$homePhone</td><td>$eMail</td><td>$address</td></tr>";
}
print "</table>";


print "</body></html>";

?>
