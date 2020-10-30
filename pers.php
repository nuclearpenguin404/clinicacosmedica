<?php 

error_reporting(E_ALL ^ E_NOTICE);

 
/*$dbhost='127.0.0.1';
$database='clinicacosmedica';
$dbuser='clinicacosmedica';
$dbpassword='L5ncAvet8uwbf96X';*/

include 'dbConfig.php';


$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 


if($_POST['oper']=='edit')
{   
    $editId=$_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $cellPhone = isset($_POST['cellPhone']) ? $_POST['cellPhone'] :'';
    $homePhone = isset($_POST['homePhone']) ? $_POST['homePhone'] :'';
    $eMail = isset($_POST['eMail']) ? $_POST['eMail'] :'';
    $dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] :'';
    $address = isset($_POST['address']) ? $_POST['address'] :'';

    $Q = "SELECT * FROM patients WHERE firstName='$firstName' AND lastName='$lastName' AND id<>'$editId'";
    $R = mysql_query($Q); 
    $exPatientsCount=mysql_num_rows($R); 

    if($exPatientsCount) {
            echo json_encode(array(status=>false,message=>'Error: patient already exists'));
            return;
    }


    $Q="UPDATE patients SET firstName='$firstName', lastName='$lastName', cellPhone='$cellPhone',  homePhone='$homePhone',  eMail='$eMail',  dateOfBirth='$dateOfBirth',  address='$address' WHERE id='$editId' ";
    $R=mysql_query($Q);
    
    //print " // $Q \n";    
    
}

if(isset($_POST['id'])&&($_POST['oper']=='del'))
{    
    
    $delId=explode(",",$_POST['id']);

    for($i=0;$i<count($delId);$i++)
    {
        //$R=mysql_query("DELETE FROM patients WHERE id='$delId[$i]'");
        $Q="DELETE FROM treatments WHERE patientId='$delId[$i]'";
        $R=mysql_query($Q);

        $Q="DELETE FROM measurements WHERE patientId='$delId[$i]'";
        $R=mysql_query($Q);

        $Q="DELETE FROM patients WHERE id='$delId[$i]'";
        $R=mysql_query($Q);
    }    
}



$page = isset($_POST['page']) ? $_POST['page'] :1; 
 
// get how many rows we want to have into the grid - rowNum parameter in the grid 
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; 
 
// get index row - i.e. user click to sort. At first time sortname parameter -
// after that the index from colModel 
$sidx = $_POST['sidx'] || '1'; 
 
// sorting order - at first time sortorder 
$sord = isset($_POST['sord']) ? $_POST['sord'] : 'asc'; 
 
// if we not pass at first time index use the first column for the index or what you want
if(!$sidx) $sidx =1; 
 
// connect to the MySQL database server 
//$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
 
// select the database 
//mysql_select_db($database) or die("Error connecting to db."); 
 


$patientId=$_GET['eid'];


// calculate the number of rows for the query. We need this for paging the result 
$result = mysql_query("SELECT COUNT(*) AS count FROM patients WHERE id='$patientId'"); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
 
// calculate the total pages for the query 
if( $count > 0 && $limit > 0) { 
              $total_pages = ceil($count/$limit); 
} else { 
              $total_pages = 0; 
} 
 
// if for some reasons the requested page is greater than the total 
// set the requested page to total page 
if ($page > $total_pages) $page=$total_pages;
 
// calculate the starting position of the rows 
$start = $limit*$page - $limit;
 
// if for some reasons start position is negative set it to 0 
// typical case is that the user type 0 for the requested page 
if($start <0) $start = 0; 
 
// the actual query for the grid data 
$SQL = "SELECT *  FROM patients where id='$patientId' ORDER BY $sidx $sord LIMIT $start , $limit"; 
//print $SQL;
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error()); 
 


$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 

$i=0; 

while($row = mysql_fetch_array($result,MYSQL_ASSOC)) { 
    $responce->rows[$i]['id']=$row['id']; 
    $responce->rows[$i]['cell']=array($row['firstName'],$row['lastName'],$row['cellPhone'],$row['homePhone'],$row['eMail'],$row['dateOfBirth'],$row['address']); 
    $i++; 
} 

echo json_encode($responce);



?>