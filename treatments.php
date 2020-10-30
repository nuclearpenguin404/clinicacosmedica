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
    $kindOfTreatment = isset($_POST['kindOfTreatment']) ? $_POST['kindOfTreatment'] :'';
    $therapistId = isset($_POST['therapist']) ? $_POST['therapist'] :'';
    $auxiliaryTherapistId = isset($_POST['auxiliaryTherapist']) ? $_POST['auxiliaryTherapist'] :'';
    $nextSessionDate = isset($_POST['nextSessionDate']) ? $_POST['nextSessionDate'] :'';


    $Q=mysql_query("INSERT INTO treatments SET patientId='$patientId', date='$date', kindOfTreatment='$kindOfTreatment', therapistId='$therapistId',  auxiliaryTherapistId='$auxiliaryTherapistId', nextSessionDate='$nextSessionDate'");
    

    $treatmentId=mysql_insert_id();
     
    
    $nrQuery = mysql_query("SELECT MAX(numberOfSessions) AS sessionNr FROM treatments WHERE patientId='$patientId'"); 
    $nrRow = mysql_fetch_array($nrQuery,MYSQL_ASSOC); 
    $sessionNr = $nrRow['sessionNr'];
    $sessionNr++;


    $Q="UPDATE treatments SET numberOfSessions='$sessionNr' WHERE id='$treatmentId'";
    $R=mysql_query($Q);
    //print " // $Q \n";
    //print mysql_error();    

    /*$Qpatient="SELECT lastName,firstName FROM patients WHERE id='$patientId'";
    $Rpatient=mysql_query($Qpatient);
    list($lastName,$firstName)=mysql_fetch_row($Rpatient);
    $patientFL="$firstName $lastName";

    $Q="INSERT INTO timetable SET date='$nextSessionDate', therapistId='$therapistId', sessionTime='',patientFL='$patientFL'";
    $R=mysql_query($Q);*/
    
    
    
}

if($_POST['oper']=='edit')
{  
    $editId=$_POST['id']; 
    $date = $_POST['date'];
    $kindOfTreatment = $_POST['kindOfTreatment'];
    $therapistId = $_POST['therapist'];
    $auxiliaryTherapistId = $_POST['auxiliaryTherapist'];
    $nextSessionDate = $_POST['nextSessionDate'];


    $Q="UPDATE treatments SET date='$date', kindOfTreatment='$kindOfTreatment', therapistId='$therapistId',  auxiliaryTherapistId='$auxiliaryTherapistId', nextSessionDate='$nextSessionDate' WHERE id='$editId'";
    $R=mysql_query($Q);
    
       
    //print " // $Q \n";
    //print mysql_error();  
    
}

if(isset($_POST['id'])&&($_POST['oper']=='del'))
{    
    
    $delId=explode(",",$_POST['id']);

    for($i=0;$i<count($delId);$i++)
    {
        $R=mysql_query("DELETE FROM treatments WHERE id='$delId[$i]'");
    }
}



$page = isset($_POST['page']) ? $_POST['page'] :1; 
 
// get how many rows we want to have into the grid - rowNum parameter in the grid 
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; 
 
// get index row - i.e. user click to sort. At first time sortname parameter -
// after that the index from colModel 
//$sidx = $_POST['sidx'] || '1';

//$sidx = isset($_POST['sidx']) ? $_POST['sidx'] :'numberOfSessions'; 

if (isset($_POST['sidx']))
{
    $arr = array("numberOfSessions" => "numberOfSessions",
        "kindOfTreatment" => "kindOfTreatment",

        "nextSessionDate" => "nextSessionDate",
        "therapist" => "d1_lastName",
        "auxiliaryTherapist" => "d2_lastName"


        );

    $sidx = isset($arr[$_POST['sidx']]) ? $arr[$_POST['sidx']] : 'numberOfSessions';

}



 
// sorting order - at first time sortorder 
$sord = isset($_POST['sord']) ? $_POST['sord'] : 'asc'; 
 
// if we not pass at first time index use the first column for the index or what you want
//if(!$sidx) $sidx =1;
if(!$sidx) $sidx ='numberOfSessions'; 


$result = mysql_query("SELECT COUNT(*) AS count FROM treatments WHERE patientId='$patientId'"); 
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


//print " // " . $_POST['_search'];
if(isset($_POST['_search']) and $_POST['_search'] != "false")
{
    $arr[] = "treatments.patientId='$patientId'";    
    
    if($_POST['numberOfSessions']) $arr[] = "treatments.numberOfSessions='" . $_POST['numberOfSessions'] . "'";
    if($_POST['date']) $arr[] = "treatments.date LIKE '%" . $_POST['date'] . "%'";
    if($_POST['kindOfTreatment']) $arr[] = "treatments.kindOfTreatment LIKE '%" . $_POST['kindOfTreatment'] . "%'";
    if($_POST['nextSessionDate']) $arr[] = "treatments.nextSessionDate LIKE '%" . $_POST['nextSessionDate'] . "%'";
    //if($_POST['therapist']) $arr[] = "treatments.therapistId='" . $_POST['therapist'] . "'";
    
    if(isset($_POST['therapist'])) {
        if($_POST['therapist'] == '0'){
            $arr[] = " d1.id IS NULL ";

        }else{
            $arr[] = "treatments.therapistId='" . $_POST['therapist'] . "'";
        }

    }

    if(isset($_POST['auxiliaryTherapist'])) {
        if($_POST['auxiliaryTherapist'] == '0'){
            $arr[] = " d2.id IS NULL ";

        }else{
            $arr[] = "treatments.auxiliaryTherapistId='" . $_POST['auxiliaryTherapist'] . "'";
        }

    }
    

    $arr = join(' AND ',$arr);
    if(!$arr) $arr = "1";

    

     $SQL = "SELECT treatments.*, d1.firstName d1_firstName,d1.lastName d1_lastName, d2.firstName d2_firstName,d2.lastName d2_lastName FROM treatments
     LEFT JOIN doctors d1 ON d1.id=treatments.therapistId LEFT JOIN doctors d2 ON d2.id = treatments.auxiliaryTherapistId WHERE $arr  ORDER BY $sidx $sord LIMIT $start , $limit";




}   
else
{
    $SQL = "SELECT treatments.*, d1.firstName d1_firstName,d1.lastName d1_lastName, d2.firstName d2_firstName,d2.lastName d2_lastName FROM treatments LEFT JOIN doctors d1 ON d1.id=therapistId LEFT JOIN doctors d2 ON d2.id = auxiliaryTherapistId WHERE patientId='$patientId' ORDER BY $sidx $sord LIMIT $start , $limit"; 
} 

//print $SQL;



$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error()); 

$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 

$i=0; 

while($row = mysql_fetch_array($result,MYSQL_ASSOC)) { 
    $responce->rows[$i]['id']=$row['id']; 
    //$responce->rows[$i]['cell']=array($row['numberOfSessions'],$row['date'],$row['kindOfTreatment'],$row['d1_firstName'],$row['auxiliaryTherapistId'],$row['nextSessionDate']); 
    
    $lastFirst1=$row['d1_lastName']." ".$row['d1_firstName'];
    $lastFirst2=$row['d2_lastName']." ".$row['d2_firstName'];

    $responce->rows[$i]['cell']=array($row['numberOfSessions'],$row['date'],$row['kindOfTreatment'],$lastFirst1,$lastFirst2,$row['nextSessionDate']); 
    
    $i++; 
} 

echo json_encode($responce);



?>