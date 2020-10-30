<?php  

error_reporting(E_ALL ^ E_NOTICE);

    
/*$dbhost='127.0.0.1';
$database='clinicacosmedica';
$dbuser='clinicacosmedica';
$dbpassword='L5ncAvet8uwbf96X';*/

include 'dbConfig.php';

$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 



if($_POST['oper']=='add')
{
    $date = isset($_POST['date']) ? $_POST['date'] :'';
    $therapistId = isset($_POST['therapist']) ? $_POST['therapist'] :'';
    $sessionTime = isset($_POST['sessionTime']) ? $_POST['sessionTime'] :'';
    $patientFL = isset($_POST['patientFL']) ? $_POST['patientFL'] :'';
    
    
    $Q="INSERT INTO timetable SET date='$date', therapistId='$therapistId', sessionTime='$sessionTime',patientFL='$patientFL'";
    $R=mysql_query($Q);
    
    //print " // $Q \n";
    //print mysql_error();    
    
}

if($_POST['oper']=='edit')
{  
    $editId=$_POST['id']; 
    $date = isset($_POST['date']) ? $_POST['date'] :'';
    $therapistId = isset($_POST['therapist']) ? $_POST['therapist'] :'';
    $sessionTime = isset($_POST['sessionTime']) ? $_POST['sessionTime'] :'';
    $patientFL = isset($_POST['patientFL']) ? $_POST['patientFL'] :'';


    $Q="UPDATE timetable SET date='$date', therapistId='$therapistId', sessionTime='$sessionTime',patientFL='$patientFL' WHERE id='$editId'";
    $R=mysql_query($Q);
    
    //print " // $Q \n";
    
}

if(isset($_POST['id'])&&($_POST['oper']=='del'))
{    
    
    $delId=explode(",",$_POST['id']);

    for($i=0;$i<count($delId);$i++)
    {
        $R=mysql_query("DELETE FROM timetable WHERE id='$delId[$i]'");
    }
}



$page = isset($_POST['page']) ? $_POST['page'] :1; 
 
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; 
 
 
$sord='asc';
$sidx ='date, sessionTime'; 




$result = mysql_query("SELECT COUNT(*) AS count FROM timetable"); 
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

$arr = array();


//$curDateArr=getdate();
$curDate=date("Y-m-d");
//print $curDate;
//return;



if(isset($_POST['_search']) and $_POST['_search'] != "false")
{
      
    
    
    if($_POST['date']) $arr[] = "timetable.date LIKE '%" . $_POST['date'] . "%'";
       
    if(isset($_POST['therapist'])) {
        if($_POST['therapist'] == '0'){
            $arr[] = " d1.id IS NULL ";

        }else{
            $arr[] = "timetable.therapistId='" . $_POST['therapist'] . "'";
        }

    }     


    if($_POST['sessionTime']) $arr[] = "timetable.sessionTime LIKE '%" . $_POST['sessionTime'] . "%'";
    if($_POST['patientFL']) $arr[] = "timetable.patientFL LIKE '%" . $_POST['patientFL'] . "%'";
    

    $arr = join(' AND ',$arr);
    if(!$arr) $arr = "1";

    
    $SQL = "SELECT timetable.*, d1.firstName d1_firstName,d1.lastName d1_lastName FROM timetable
    LEFT JOIN doctors d1 ON d1.id=timetable.therapistId WHERE $arr 
    ORDER BY date,sessionTime LIMIT $start , $limit";

    //print $SQL;
    //print mysql_error();

}   
else
{
    $SQL = "SELECT timetable.*, d1.firstName d1_firstName,d1.lastName d1_lastName FROM timetable
    LEFT JOIN doctors d1 ON d1.id=timetable.therapistId 
    ORDER BY date, sessionTime asc LIMIT $start , $limit";


    /*$SQL = "SELECT timetable.*, d1.firstName d1_firstName,d1.lastName d1_lastName FROM timetable 
    LEFT JOIN doctors d1 ON d1.id=timetable.therapistId WHERE timetable.date>='$curDate' 
    ORDER BY date, sessionTime asc LIMIT $start , $limit";*/

} 

//print $SQL;




$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error()); 

$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 

$i=0; 

while($row = mysql_fetch_array($result,MYSQL_ASSOC)) { 
    $responce->rows[$i]['id']=$row['id']; 
    
    
    $lastFirst1=$row['d1_lastName']." ".$row['d1_firstName'];
    

    $responce->rows[$i]['cell']=array($row['date'],$lastFirst1,$row['sessionTime'],$row['patientFL']); 
    
    $i++; 
} 

echo json_encode($responce);



?>