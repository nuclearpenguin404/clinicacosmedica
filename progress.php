<?php 

error_reporting(E_ALL ^ E_NOTICE);

    
/*$dbhost='127.0.0.1';
$database='clinicacosmedica';
$dbuser='clinicacosmedica';
$dbpassword='L5ncAvet8uwbf96X';*/

include 'dbConfig.php';


$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 


$patientId=$_GET['eid'];


if($_POST['oper']=='add')
{
    $date = isset($_POST['date']) ? $_POST['date'] :'';
    $weight = isset($_POST['weight']) ? $_POST['weight'] :'';
    $waistMeasure = isset($_POST['waistMeasure']) ? $_POST['waistMeasure'] :'';
    $hipMeasure = isset($_POST['hipMeasure']) ? $_POST['hipMeasure'] :'';
    $armMeasure = isset($_POST['armMeasure']) ? $_POST['armMeasure'] :'';


    $Q=mysql_query("INSERT INTO measurements SET patientId='$patientId', date='$date', weight='$weight', waistMeasure='$waistMeasure',  hipMeasure='$hipMeasure', armMeasure='$armMeasure'");
    

    $measurementsId=mysql_insert_id();
     
     //глючит при удалении части записей  
    /*$nrQuery = mysql_query("SELECT COUNT(*) AS measurementNr FROM measurements WHERE patientId='$patientId'"); 
    $nrRow = mysql_fetch_array($nrQuery,MYSQL_ASSOC); 
    $measurementNr = $nrRow['measurementNr'];*/


    $nrQuery = mysql_query("SELECT MAX(measurementNr) AS measurementNr FROM measurements WHERE patientId='$patientId'"); 
    $nrRow = mysql_fetch_array($nrQuery,MYSQL_ASSOC); 
    $measurementNr = $nrRow['measurementNr'];
    $measurementNr++;


    $Q="UPDATE measurements SET measurementNr='$measurementNr' WHERE id='$measurementsId'";
    $R=mysql_query($Q);
    //print " // $Q \n";
    //print mysql_error();

    //неплохо бы проадейтить саму таблицу после вычисления номера измерения
    
}

if($_POST['oper']=='edit')
{   
    $editId=$_POST['id'];
    $date=$_POST['date'];
    $weight=$_POST['weight'];
    $waistMeasure=$_POST['waistMeasure'];
    $hipMeasure=$_POST['hipMeasure'];
    $armMeasure=$_POST['armMeasure'];


    $Q="UPDATE measurements SET patientId='$patientId', date='$date', weight='$weight', waistMeasure='$waistMeasure',  hipMeasure='$hipMeasure',  armMeasure='$armMeasure' WHERE id='$editId'";
    $R=mysql_query($Q);
    
    //print " // $Q \n";
    
}

if(isset($_POST['id'])&&($_POST['oper']=='del'))
{    
    
    $delId=explode(",",$_POST['id']);

    for($i=0;$i<count($delId);$i++)
    {
        $R=mysql_query("DELETE FROM measurements WHERE id='$delId[$i]'");
    }
}



$page = isset($_POST['page']) ? $_POST['page'] :1; 
 
// get how many rows we want to have into the grid - rowNum parameter in the grid 
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; 
 
// get index row - i.e. user click to sort. At first time sortname parameter -
// after that the index from colModel 
//$sidx = $_POST['sidx'] || '1'; 
$sidx='measurementNr';
 
// sorting order - at first time sortorder 
//$sord = isset($_POST['sord']) ? $_POST['sord'] : 'asc'; 
$sord='asc';
 
// if we not pass at first time index use the first column for the index or what you want
if(!$sidx) $sidx ='measurementNr'; 
 
// connect to the MySQL database server 
//$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
 
// select the database 
//mysql_select_db($database) or die("Error connecting to db."); 
 
// calculate the number of rows for the query. We need this for paging the result 
$result = mysql_query("SELECT COUNT(*) AS count FROM measurements WHERE patientId='$patientId'"); 
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


//print " // " . $_POST['_search'];
if(isset($_POST['_search']) and $_POST['_search'] != "false")
{
    $arr[] = "patientId='$patientId'";    
    
    if($_POST['Nr']) $arr[] = "measurementNr='" . $_POST['Nr'] . "'";
    if($_POST['date']) $arr[] = "date LIKE '%" . $_POST['date'] . "%'";
    if($_POST['weight']) $arr[] = "weight='" . $_POST['weight'] . "'";
    if($_POST['waistMeasure']) $arr[] = "waistMeasure='" . $_POST['waistMeasure'] . "'";
    if($_POST['hipMeasure']) $arr[] = "hipMeasure='" . $_POST['hipMeasure'] . "'";
    if($_POST['armMeasure']) $arr[] = "armMeasure='" . $_POST['armMeasure'] . "'";


    $arr = join(' AND ',$arr);
    if(!$arr) $arr = "1";

    $SQL = "SELECT * FROM measurements WHERE $arr ORDER BY $sidx $sord LIMIT $start , $limit";

}   
else
{
    $SQL = "SELECT * FROM measurements WHERE patientId='$patientId' ORDER BY $sidx $sord LIMIT $start , $limit";
} 

//print $SQL;
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error()); 
 


$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 

$i=0; 

while($row = mysql_fetch_array($result,MYSQL_ASSOC)) { 
    $responce->rows[$i]['id']=$row['id']; 
    $responce->rows[$i]['cell']=array($row['measurementNr'],$row['date'],$row['weight'],$row['waistMeasure'],$row['hipMeasure'],$row['armMeasure']); 
    $i++; 
} 

echo json_encode($responce);



?>