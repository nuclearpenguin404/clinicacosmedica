<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	

	<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.9.0.custom.css" />
	<link rel="stylesheet" type="text/css" href="js/src/css/ui.jqgrid.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />


	<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
 	<script src="js/jquery-ui-1.9.0.custom.js" type="text/javascript"></script>
	<script src="js/src/i18n/grid.locale-en.js" type="text/javascript"></script>
	<script src="js/src/grid.loader.js" type="text/javascript"></script>
	<script src="js/jquery.jqGrid.src.js" type="text/javascript"></script>




 	<!--<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>

	<script src="js/jquery.validationEngine-en.js" type="text/javascript"></script>
	<script src="js/jquery.validationEngine.js" type="text/javascript"></script>-->

	<link rel="shortcut icon" href="hamster.ico" />



 	<script type="text/javascript">
		jQuery(document).ready(function(){ 
			var lastsel; 

			$(function() {
        		$( "#datepicker" ).datepicker({
            	changeMonth: true,
            	changeYear: true
       			});

        		$( "#format" ).change(function() {
            		$( "#datepicker" ).datepicker( "option", "dateFormat", $( this ).val() );
       			});

   			 });


			
			var errorMessage = function(response,postdata){
		        var json   = response.responseText;
		        var result=JSON.parse(json);
		        return [result.status,result.message,null];
		    }


		    var destId = location.href.replace(/^.*?\=/, '');
		    //alert(destId);



			var mygrid = jQuery("#patientsList").jqGrid({
			    url:'persDoct.php?eid='+destId,
			    datatype: "json",
			    mtype: 'POST',
			    colNames:['First Name', 'Last Name','Cell Phone','Home Phone','e-mail','Date of Birth','Address'],
			    colModel:[
			        {name:'firstName',index:'firstName', width:150, editable:true,editrules:{required: true},sorttype:'text'},
			        {name:'lastName',index:'lastName', width:150, editable:true,editrules:{required: true},sorttype:'text'},
			        {name:'cellPhone',index:'cellPhone', width:150, editable:true,sorttype:'text'},
			        {name:'homePhone',index:'homePhone', width:150, editable:true,sorttype:'text'},
			        {name:'eMail',index:'eMail', width:150, editable:true,sorttype:'text'},
			        {name:'dateOfBirth',index:'dateOfBirth',sorttype:'date',width:100, editable:true, editoptions:{size:20, 
                		dataInit:function(el){ 
                   			$(el).datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,date:['1930','2050']}) 
                		}
			       	},},
			       	/*{name:'dateOfBirth',index:'dateOfBirth', width:90,searchoptions:{
			       		dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,date:['1930','2050']});} 
			       	}},*/
			       	{name:'address',index:'address', width:200, editable:true,sorttype:'text'}
			    ],
			    rowNum:20,
			    rowList:[10,20,30],
			    //gridview: true,
			    multiselect: true,
			    pager: '#pgPatientsList',
			    sortname: 'firstName',
			    viewrecords: true,
			    sortorder: "desc",
			    caption:"Patients",
			    rownumbers: true,
			    editurl:"patients.php",
			    height: 'auto',


				ondblClickRow: function(id){ 
					//alert(id);			       
					window.open('pers_m.php?id='+id,'_blank');
				}

			});
			

			
			
			jQuery("#patientsList").jqGrid('navGrid','#pgPatientsList',{edit:false,add:false,del:false,search:false,refresh:true}); 
			jQuery("#patientsList").jqGrid('filterToolbar',{searchOnEnter:false}); 


			$("#toggleSearchPanel").click(function(){ 
				mygrid[0].toggleToolbar(); 
			});

			$("#clearSearchPanel").click(function(){ 
				mygrid[0].clearToolbar();
			});

		});

	</script>

</head>
<body>

<div id="nav">
	<ul id="menu-top" class="ui-menu">
		<li class="ui-menu-item"><a href="patients_m.html">Patients</a></li>
		<li class="ui-menu-item"><a href="doctors_m.html">Doctors</a></li>
		<li class="ui-menu-item"><a href="timetable_m.php">Timetable</a></li>
	</ul>
</div>


<?php

	$iid = $_GET['id'];  //location.href.replace(/^.*?\=/, '');
	
	//print_r($_REQUEST);


	include 'dbConfig.php';

	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
	mysql_select_db($database) or die("Error connecting to db."); 

	$Q="SELECT firstName,lastName FROM doctors WHERE id='$iid'";
	$R=mysql_query($Q);
	//print $Q.mysql_error();
	list($ffirstName,$llastName)=mysql_fetch_row($R);

?>



<h2>Therapist: <?php print $ffirstName;?>&nbsp;<?php print $llastName;?></h2>


<div id="patientsNote">Note: Double click on patient's record to edit treatments and progress data</div>

<div id="dialogSelect"></div>

<div>
	<table id="patientsList"></table>
	<div id="pgPatientsList"></div>
	<div id="filterToolbar">Search Patients</div>
</div>

<div class="buttonsDiv">
    <input type="button" class="ui-button" value="Toggle Search Panel" id="toggleSearchPanel"/>    
    <input type="button" class="ui-button" value="Clear Search" id="clearSearchPanel"/>        
</div>

<div class="clr"></div>


</body>
</html>