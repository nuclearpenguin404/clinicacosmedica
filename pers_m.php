<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	

	<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.9.0.custom.css" />
	<link rel="stylesheet" type="text/css" href="js/src/css/ui.jqgrid.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />


	<link rel="shortcut icon" href="hamster.ico" />
	 
	
	<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
 	<script src="js/jquery-ui-1.9.0.custom.js" type="text/javascript"></script>
	<script src="js/src/i18n/grid.locale-en.js" type="text/javascript"></script>
	<script src="js/src/grid.loader.js" type="text/javascript"></script>
	<script src="js/jquery.jqGrid.src.js" type="text/javascript"></script>

	<link rel="stylesheet" type="text/css" href="jqplot/jquery.jqplot.css" />
	<!--[if IE]><script type="text/javascript" src="js/excanvas.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="jqplot/jquery.jqplot.min.js"></script>
	<script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.json2.min.js"></script>
	<script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
	<script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>

	<script src="js/validationFunctions.js" type="text/javascript"></script>


	<link rel="shortcut icon" href="hamster.ico" />
	
	<script type="text/javascript">
		<?php
			require "doctors4Treatments.php";
		?>
	</script>



 	<script type="text/javascript">

	 	var destId;

		function submitXLS() {

			location.href = 'patientsDataXls.php?id='+destId;
			return false;
		}

		jQuery(document).ready(function(){ 
		 
		$( "#tabs" ).tabs({
            beforeLoad: function( event, ui ) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                        "Couldn't load this tab. We'll try to fix this as soon as possible. " );
                });
            }
        });

		$(function() {
        		$( "#datepicker" ).datepicker({
            	changeMonth: true,
            	changeYear: true
       			});

        		$( "#format" ).change(function() {
            		$( "#datepicker" ).datepicker( "option", "dateFormat", $( this ).val() );
       			});

   			 });

		$(function() {
		    $("#dialogSelect").dialog({
		        modal: true,
		        autoOpen: false,
		        buttons: {
		            Ok: function() {
		                $( this ).dialog( "close" );
		            }
		        }
		    });
		});

		destId = location.href.replace(/^.*?\=/, '');
		$('#link_progress').attr('href', $('#link_progress').attr('href') + '?id=' + destId);
		$('#exportLink').attr('href', $('#exportLink').attr('href') + '?id=' + destId);
		$('#exportLink2').attr('href', $('#exportLink2').attr('href') + '?id=' + destId);
		
		//alert(destId);	


		var errorMessage = function(response,postdata){
		        var json   = response.responseText;
		        var result=JSON.parse(json);
		        return [result.status,result.message,null];
		}

		var patientsRedirect = function(){
			location.href='patients_m.html';
		}


		var mygrid = jQuery("#persData").jqGrid({
		  	url:'pers.php?eid='+destId,
		   	datatype: "json",
		   	mtype: 'POST',
		   	colNames:['First Name', 'Last Name','Cell Phone','Home Phone','e-mail','Date of Birth','Address'],
		  	colModel:[
			    {name:'firstName',index:'firstName', width:150, editable:true,sorttype:'text', editoptions:{maxlength:60},
			       	editrules:{required:true, custom:true, custom_func:nameValidation}
			    },
			    {name:'lastName',index:'lastName', width:150, editable:true,sorttype:'text', editoptions:{maxlength:60},
			       	editrules:{required:true, custom:true, custom_func:nameValidation}
			    },
			    //{name:'cellPhone',index:'cellPhone', width:150, editable:true,sorttype:'text'},
			    {name:'cellPhone',index:'cellPhone', width:150, editable:true, sorttype:'text', editoptions:{maxlength:60},
			       	editrules: {required:false,custom:true,custom_func:phoneValidation}
			    },			        
			    //{name:'homePhone',index:'homePhone', width:150, editable:true,sorttype:'text'},
			    {name:'homePhone',index:'homePhone',width:150, editable:true,sorttype:'text', editoptions:{maxlength:60},
			       	editrules: {required:false,	custom: true, custom_func: phoneValidation}
			    },
			    //{name:'eMail',index:'eMail', width:150, editable:true,sorttype:'text'},
			    {name:'eMail',index:'eMail',width:150, editable:true,sorttype:'text', editoptions:{maxlength:60},
			       	editrules: {required:false, custom:true, custom_func:emailValidation}
			    },
			    {name:'dateOfBirth',index:'dateOfBirth',sorttype:'date',width:100, editable:true, editoptions:{maxlength:60},
			       	editoptions:{
			       		size:20, 
                		dataInit:function(el){ 
                			$(el).datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,date:['1930','2050']}) 
                			}
			    	},
			    },
			    {name:'address',index:'address', width:200, editable:true,sorttype:'text',
			    	edittype:'textarea', editoptions:{rows:3,wrap:'hard',sorttype:'text',maxlength:300}
			    }
			],
		   	rowNum:1,
		   	viewrecords: true,
		   	caption:"Personal Data",
		   	editurl:'pers.php?eid='+destId,
		   	height: 'auto',		   	
		   	pager: jQuery('#pgPersData'),
		   	rownumbers: true,
		   	//multiselect: true,
		});


				
		$("#editPatientData").click(function(){ 
			var firstRowId=$("#persData").getDataIDs()[0];
			jQuery("#persData").jqGrid('editGridRow',firstRowId,{reloadAfterSubmit:false,closeAfterEdit:true,afterSubmit:errorMessage}); 
			
		});
						
		
		$("#delPatient").click(function(){ 
			var firstRowId=$("#persData").getDataIDs()[0];
			jQuery("#persData").jqGrid('delGridRow',firstRowId,{reloadAfterSubmit:true,afterSubmit:patientsRedirect});									
		});
			
		
		jQuery("#persData").jqGrid('navGrid','#pgPersData',{edit:false,add:false,del:false,search:false,refresh:false}); 
		
		
				
		var treatmentsGrid = jQuery("#treatments").jqGrid({
		    url:'treatments.php?eid='+destId,
		    datatype: "json",
		    mtype: 'POST',
		    colNames:['Session Nr.','Date', 'Kind Of Treatment','Therapist','Auxiliary Therapist','Next Session Date'],
		    colModel:[
		    	{name:'numberOfSessions',index:'numberOfSessions',width:60,editable:false,sorttype:'int'},
		        {name:'date',index:'date', width:100,sorttype:'date',editable:true,editrules:{required: true},
		        	editoptions:{
		        		size:20, 
		        		maxlength:60,
	           			dataInit:function(el){ 
	           			$(el).datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,date:['1990','2050']}) 
	           			}
					}
				},
		        {name:'kindOfTreatment',index:'kindOfTreatment',width:400,editable:true,editrules:{required: true},edittype:'textarea', editoptions:{rows:"5",cols:"35",wrap:'hard',sorttype:'text'}},
		        {name:'therapist',index:'therapist',width:200,editable:true,
		        	edittype:'select',editoptions:{value:doctorsActive},
		        	stype: 'select', searchoptions: { sopt: ['eq', 'ne'], value:doctors,sorttype:'text'}
		        },
		        {name:'auxiliaryTherapist',	index:'auxiliaryTherapist', width:200, 
		        	editoptions:{value:doctorsActive},editable:true,edittype:'select',
		        	stype: 'select', searchoptions: { sopt: ['eq', 'ne'], value:doctors,sorttype:'text'}
		        },
		        {name:'nextSessionDate',index:'nextSessionDate', width:100, editable:true,sorttype:'date', 
		        	editoptions:{size:20, maxlength:60,
	            		dataInit:function(el){ 
	                		$(el).datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,date:['1990','2050']}) 
	                	}
					}
				}
		    ],
		    rowNum:20,
		    rowList:[10,20,30],
		    //gridview: true,
		    multiselect: true,
		    pager: jQuery('#pgTreatments'),
		    viewrecords: true,
		    caption:"Treatments",
		    rownumbers: true,
		    editurl:'treatments.php?eid='+destId		    

		});

		jQuery("#treatments").jqGrid('navGrid',"#pgTreatments",{edit:false,add:false,del:false,search:false,refresh:false}); 
		jQuery("#treatments").jqGrid('filterToolbar',{searchOnEnter:false}); 


		
		$("#addTreatment").click(function(){ 
			jQuery("#treatments").jqGrid('editGridRow',"new",{
			reloadAfterSubmit:true,
			closeAfterAdd:true,
			width: 395,
			height: 264
			}); 
		});




		$("#editTreatment").click(function(){ 
			var gr = jQuery("#treatments").jqGrid('getGridParam','selrow'); 
			if( gr != null ) 
				jQuery("#treatments").jqGrid('editGridRow',gr,{reloadAfterSubmit:false,closeAfterEdit:true,afterSubmit:errorMessage,width: 395,
			height: 264}); 
			else 
			{
				//alert("Please Select Row"); 
				$("#dialogSelect").html('Please select row!');
				$("#dialogSelect").dialog('open');
			}	
		});

				

		$("#delTreatment").click(function(){ 
			var gr0 = jQuery("#treatments").jqGrid('getGridParam','selrow'); 
			
			if( gr0!= null ){
				//alert(gr.length);
				var gr = jQuery("#treatments").jqGrid('getGridParam','selarrrow'); 
				jQuery("#treatments").jqGrid('delGridRow',gr.toString(),{reloadAfterSubmit:false}); 
			}
			else{
				//alert("Please Select Row"); 
				$("#dialogSelect").html('Please select row!');
				$("#dialogSelect").dialog('open');
			}
		});
			

		
		$("#toggleSearchPanel").click(function(){ 
			treatmentsGrid[0].toggleToolbar(); 
		});

		$("#clearSearchPanel").click(function(){ 
			treatmentsGrid[0].clearToolbar();
		});



    	

	});

</script>


<style type="text/css">

	div.buttonsDiv{
		margin: 10px 0px 30px 0px;
		font-size: 12px;

	}	

</style>

</head>
<body>


<?php

	$iid = $_GET['id'];  //location.href.replace(/^.*?\=/, '');
	
	//print_r($_REQUEST);


	include 'dbConfig.php';

	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
	mysql_select_db($database) or die("Error connecting to db."); 

	$Q="SELECT firstName,lastName FROM patients WHERE id='$iid'";
	$R=mysql_query($Q);
	//print $Q.mysql_error();
	list($ffirstName,$llastName)=mysql_fetch_row($R);

?>


<div id="nav">
	<ul id="menu-top" class="ui-menu">
		<li class="ui-menu-item"><a href="patients_m.html">Patients</a></li>
		<li class="ui-menu-item"><a href="doctors_m.html">Doctors</a></li>
		<li class="ui-menu-item"><a href="timetable_m.php">Timetable</a></li>
	</ul>
</div>

<div id="tabs">
    <ul>
        <li><a href="#persDataTab">Personal Data / Treatments</a></li>
        <li><a id="link_progress" href="progress_m.php">Progress</a></li>        
    </ul>
    <div id="persDataTab">

    	<h2>Patient: <?php print $ffirstName;?>&nbsp;<?php print $llastName;?></h2>

    	

        <div>
    		<table id="persData"></table>  
    		<div id="pgPersData"></div> 			
		</div>
		<div class="buttonsDiv">
		    <input type="button" class="ui-button" value="Edit Patient's Data" id="editPatientData" />
		    <input type="button" class="ui-button" value="Delete Patient" id="delPatient"/>    		    
		</div>

		<div id="dialogSelect"></div>
		
    	<div>
    		<table id="treatments"></table>  
    		<div id="pgTreatments"></div>
    		<div id="filterToolbar">Search Treatments</div>
		</div>
		<div class="buttonsDiv left">
		    <input type="button" class="ui-button" value="Add Record" id="addTreatment" />
		    <input type="button" class="ui-button" value="Edit Record" id="editTreatment" />
		    <input type="button" class="ui-button" value="Delete Record" id="delTreatment"/>    
		    <input type="button" class="ui-button" value="Toggle Search Panel" id="toggleSearchPanel"/>    
		    <input type="button" class="ui-button" value="Clear Search" id="clearSearchPanel"/>
		    <input type="button" class="ui-button" value="Export to XLS" onClick="submitXLS();" />	
		</div>


		<div class="clr"></div>

    </div>
</div>




</body>
</html>