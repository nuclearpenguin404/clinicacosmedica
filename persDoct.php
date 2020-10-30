<?php 

error_reporting(E_ALL ^ E_NOTICE);

/*$dbhost='127.0.0.1';
$database='clinicacosmedica';
$dbuser='clinicacosmedica';
$dbpassword='L5ncAvet8uwbf96X';*/

include 'dbConfig.php';

$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
mysql_select_db($database) or die("Error connecting to db."); 


$doctorId=$_GET['eid'];



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





//Проверить, правильно ли отрабатывает запрос

$Q2="SELECT DISTINCT patientId FROM treatments WHERE (therapistId='$doctorId' OR auxiliaryTherapistId='$doctorId')";
$R2=mysql_query($Q2);
//print mysql_error();
//print $R2."\n";


/*if(isset($_POST['_search']) and $_POST['_search'] != "false")
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
    $SQL = "SELECT * FROM patients ORDER BY $sidx $sord LIMIT $start , $limit";       

} */


//print "//$SQL\n";


$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 


 

//Вроде работает, но на 100% сказать не могу

/*$i=0;
while ($row2=mysql_fetch_array($R2))
{
    $SQL = "SELECT * FROM patients WHERE id='$row2[patientId]' ORDER BY $sidx $sord LIMIT $start , $limit"; 
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error()); 

    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) { 
        $responce->rows[$i]['id']=$row['id']; 
        $responce->rows[$i]['cell']=array($row['firstName'],$row['lastName'],$row['cellPhone'],$row['homePhone'],$row['eMail'],$row['dateOfBirth'],$row['address']); 
        $i++; 
    }


}*/



//Проверить на большой выборке данных....

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

    //$SQL = "SELECT * FROM patients WHERE $arr ORDER BY $sidx $sord LIMIT $start , $limit";


    $i=0;
    while ($row2=mysql_fetch_array($R2))
    {
        $SQL = "SELECT * FROM patients WHERE (id='$row2[patientId]' AND $arr) ORDER BY $sidx $sord LIMIT $start , $limit"; 
        $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error()); 

        while($row = mysql_fetch_array($result,MYSQL_ASSOC)) { 
            $responce->rows[$i]['id']=$row['id']; 
            $responce->rows[$i]['cell']=array($row['firstName'],$row['lastName'],$row['cellPhone'],$row['homePhone'],$row['eMail'],$row['dateOfBirth'],$row['address']); 
            $i++; 
        }
    }


}   
else
{
    //$SQL = "SELECT * FROM patients ORDER BY $sidx $sord LIMIT $start , $limit";       

    $i=0;
    while ($row2=mysql_fetch_array($R2))
    {
        $SQL = "SELECT * FROM patients WHERE id='$row2[patientId]' ORDER BY $sidx $sord LIMIT $start , $limit"; 
        $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error()); 

        while($row = mysql_fetch_array($result,MYSQL_ASSOC)) { 
            $responce->rows[$i]['id']=$row['id']; 
            $responce->rows[$i]['cell']=array($row['firstName'],$row['lastName'],$row['cellPhone'],$row['homePhone'],$row['eMail'],$row['dateOfBirth'],$row['address']); 
            $i++; 
        }
    }
} 





echo json_encode($responce);



?>