<?php 

error_reporting(E_ALL ^ E_NOTICE);

include 'dbConfig.php';

$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 


//$Q = "SELECT id,isActive,firstName,lastName FROM doctors ORDER BY lastName"; 
$Q = "SELECT id,isActive,firstName,lastName FROM doctors WHERE isActive='1' ORDER BY lastName"; 
//print $SQL;
$R = mysql_query($Q) or die("Couldn't execute query.".mysql_error()); 

$arr = array(); 

//$arr[]="0: Select therapist";
$arr[]=": Select therapist";
$arr[]="0: None";

while($row = mysql_fetch_row($R)) { 

    $arr[] = "$row[0]:$row[3] $row[2]";

    
} 


print "var doctorsActive = '". join(';',$arr) . "';"; 


$Q2 = "SELECT id,isActive,firstName,lastName FROM doctors WHERE isActive='0' ORDER BY lastName"; 
$R2 = mysql_query($Q2) or die("Couldn't execute query.".mysql_error()); 


//$arr[] = "-:------------";    
//$arr[]="0: ";


while($row = mysql_fetch_row($R2)) { 

    $arr[] = "$row[0]:<span class=\"pale\">$row[3] $row[2]</span>"; 
   // $arr[] = "$row[0]:$row[3] $row[2]";
   
} 


print "var doctors = '". join(';',$arr) . "';"; 





?>