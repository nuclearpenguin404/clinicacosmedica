<?php 

error_reporting(E_ALL ^ E_NOTICE);

/*$dbhost='127.0.0.1';
$database='clinicacosmedica';
$dbuser='clinicacosmedica';
$dbpassword='L5ncAvet8uwbf96X';*/

include 'dbConfig.php';

$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 

if(isset($_POST['firstName'])&& isset($_POST['lastName'])&&($_POST['oper']=='add'))
{
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] :'';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] :'';

    $cellPhone = isset($_POST['cellPhone']) ? $_POST['cellPhone'] :'';
    $homePhone = isset($_POST['homePhone']) ? $_POST['homePhone'] :'';
    $eMail = isset($_POST['eMail']) ? $_POST['eMail'] :'';


    $Q = "SELECT * FROM patients WHERE firstName='$firstName' AND lastName='$lastName' "; 
    
    //не работает. да и не должен
    // допустим, сравнивается пациент, у которого определён только мобильник с пациентом, у которого только домашний.
    // они будут считаться разными, хотя по факту могут быть одним и тем же пациентом
    //$Q = "SELECT * FROM patients WHERE firstName='$firstName' AND lastName='$lastName' AND (cellPhone='$cellPhone' OR homePhone='$homePhone' OR eMail='$eMail')"; 

    $R = mysql_query($Q); 
    $exPatientsCount=mysql_num_rows($R);

    
    if($exPatientsCount) {

        echo json_encode(array(status=>false,message=>'Error: patient already exists'));
        return;
    }
  
    $dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] :'';
    $address = isset($_POST['address']) ? $_POST['address'] :'';

    $R=mysql_query("INSERT INTO patients SET firstName='$firstName', lastName='$lastName', cellPhone='$cellPhone',  homePhone='$homePhone',  eMail='$eMail',  dateOfBirth='$dateOfBirth',  address='$address'");
    
    //print mysql_error();
    $patientId=mysql_insert_id();
}



if($_POST['oper']=='edit')
{   
    $editId=$_POST['id'];
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] :'';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] :'';
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
    
    //$delId=$_POST['id'];
    $delId=explode(",",$_POST['id']);

    for($i=0;$i<count($delId);$i++)
    {
        $Q="DELETE FROM treatments WHERE patientId='$delId[$i]'";
        $R=mysql_query($Q);

        $Q="DELETE FROM measurements WHERE patientId='$delId[$i]'";
        $R=mysql_query($Q);

        $Q="DELETE FROM patients WHERE id='$delId[$i]'";
        $R=mysql_query($Q);
    }
}



/**/



$page = isset($_POST['page']) ? $_POST['page'] :1; 
 
// get how many rows we want to have into the grid - rowNum parameter in the grid 
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; 
 
// get index row - i.e. user click to sort. At first time sortname parameter -
// after that the index from colModel 
//$sidx = $_POST['sidx'] || '1'; 
$sidx = isset($_POST['sidx']) ? $_POST['sidx'] :'firstName';
 
// sorting order - at first time sortorder 
$sord = isset($_POST['sord']) ? $_POST['sord'] : 'asc'; 
 
// if we not pass at first time index use the first column for the index or what you want
//if(!$sidx) $sidx =1; 
 


$result = mysql_query("SELECT COUNT(*) AS count FROM patients"); 
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


//print $_POST['_search']. "\n";

$arr = array();


//print " // " . $_POST['_search'];
if(isset($_POST['_search']) and $_POST['_search'] != "false")
{
    //$arr[] = "patientId='$patientId'";    

    
    if($_POST['firstName']) $arr[] = "firstName LIKE '%" . $_POST['firstName'] . "%'";
    if($_POST['lastName']) $arr[] = "lastName LIKE '%" . $_POST['lastName'] . "%'";
    if($_POST['cellPhone']) $arr[] = "cellPhone LIKE '%" . $_POST['cellPhone'] . "%'";
    if($_POST['homePhone']) $arr[] = "homePhone LIKE '%" . $_POST['homePhone'] . "%'";
    if($_POST['eMail']) $arr[] = "eMail LIKE '%" . $_POST['eMail'] . "%'";
    if($_POST['dateOfBirth']) $arr[] = "dateOfBirth LIKE '%" . $_POST['dateOfBirth'] . "%'";
    if($_POST['address']) $arr[] = "address LIKE '%" . $_POST['address'] . "%'";

    $arr = join(' AND ',$arr);
    if(!$arr) $arr = "1";    

    $SQL = "SELECT * FROM patients WHERE $arr ORDER BY $sidx $sord LIMIT $start , $limit";
}   
else
{
    $SQL = "SELECT *  FROM patients ORDER BY $sidx $sord LIMIT $start , $limit";
} 

//print "//$SQL\n";


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