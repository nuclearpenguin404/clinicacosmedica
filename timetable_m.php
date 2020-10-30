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
	<script src="js/jquery.maskedinput-1.3.js" type="text/javascript"></script>


	<script src="js/validationFunctions.js" type="text/javascript"></script>

	<link rel="shortcut icon" href="hamster.ico" />


	<script type="text/javascript">
		<?php
			require "doctors4Treatments.php";
		?>
	</script>




 	<script type="text/javascript">
		jQuery(document).ready(function(){ 




			var errorMessage = function(response,postdata){
		        var json   = response.responseText;
		        var result=JSON.parse(json);
		        return [result.status,result.message,null];
		    }


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



			//$.mask.definitions['5']='[0-5]';
			//$.mask.definitions['2']='[0-2]';
			


			var timetableGrid = jQuery("#timetable").jqGrid({
		    url:'timetable.php',
		    datatype: "json",
		    mtype: 'POST',
		    rowNum: 30, 
		    	rowList: [10,20,30], 
		    	colNames:['Date', 'Therapist','Session time','Patient'], 
		    	colModel:[ 
		    		{name:'date',index:'date', width:100,sorttype:'date',editable:true,editrules:{required: true},
		    			editoptions:{
		    				maxlength:60,
		    				size:20, 
		           			dataInit:function(el){ 
		           				$(el).datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,date:['2012','2050']}) 
		           			}
						},
						searchoptions: {
							sopt: ['eq', 'ne'],
							/*dataInit:function(el){ 
		           				$(el).datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,date:['2012','2050']}) 
		           			}*/
						}

					},
					{
			        	name:'therapist',
			        	index:'therapist', 
			        	width:200, 
			        	editable:true,edittype:'select',editoptions:{value:doctorsActive},
			        	stype: 'select', searchoptions: { sopt: ['eq', 'ne'], value:doctorsActive,sorttype:'text'}

			        },
			        {
			        	name: 'sessionTime', 
			        	index: 'sessionTime', 
			        	width: 70, 
			        	editable: true,
			        	editoptions: {dataInit: function (elem) { $(elem).mask("99:99-99:99"); }},			        	
    					editrules: {
    						custom: true, 
    						custom_func: function (value) {
        						if (/^(([01]?[0-9]|2[0-3]):[0-5][0-9])-(([01]?[0-9]|2[0-3]):[0-5][0-9])$/.test(value)) 
        						{
            						return [true, ""];   
            					} 
            					else 
            					{
            						return [false, "The time "+value+" is wrong.<br/>Please enter the time in the form <b>hh:mm</b>"];
            					}
            				}
            			}
            		},

			        {name:'patientFL',index:'patientFL',width:220,
			        	editable:true,editrules:{required:true,custom:true,custom_func:nameValidation},editoptions:{maxlength:60}
			        }		   
		    		
		    	],
		    
		    height: 'auto',  
		    grouping:true, 
		    multiselect: true,
		    groupingView : { groupField : ['date'] },
		    sortname: 'sessionTime', 
		    pager: jQuery('#pgTimetable'),
		    viewrecords: true,
		    caption:"Timetable",
		    rownumbers: true,
		    editurl:'timetable.php'

		});
					
			

		jQuery("#timetable").jqGrid('navGrid',"#pgTimetable",{edit:false,add:false,del:false,search:false,refresh:false}); 
		jQuery("#timetable").jqGrid('filterToolbar',{searchOnEnter:false}); 

			
		$("#addRow").click(function(){ 
			jQuery("#timetable").jqGrid('editGridRow',"new",{
				reloadAfterSubmit:true,
				closeAfterAdd:true,
				afterSubmit:errorMessage
			}); 
		});		



		$("#editRow").click(function(){ 
			var gr = jQuery("#timetable").jqGrid('getGridParam','selrow'); 
			if( gr != null ) 
				jQuery("#timetable").jqGrid('editGridRow',gr,{reloadAfterSubmit:true,closeAfterEdit:true,afterSubmit:errorMessage,width: 395,
			height: 264}); 
			else 
			{
				//alert("Please Select Row"); 
				$("#dialogSelect").html('Please select row!');
				$("#dialogSelect").dialog('open');
			}	
		});


		

		$("#delRow").click(function(){ 
			var gr0 = jQuery("#timetable").jqGrid('getGridParam','selrow'); 
			
			if( gr0!= null ){
				//alert(gr.length);
				var gr = jQuery("#timetable").jqGrid('getGridParam','selarrrow'); 
				jQuery("#timetable").jqGrid('delGridRow',gr.toString(),{reloadAfterSubmit:false}); 
			}
			else{
				//alert("Please Select Row"); 
				$("#dialogSelect").html('Please select row!');
				$("#dialogSelect").dialog('open');
			}
		});

		

		jQuery("#timetable").jqGrid('navGrid','#pgTimetable',{edit:false,add:false,del:false,search:false,refresh:true}); 
		jQuery("#timetable").jqGrid('filterToolbar',{searchOnEnter:false}); 


		$("#toggleSearchPanel").click(function(){ 
			timetableGrid[0].toggleToolbar(); 
		});

		$("#clearSearchPanel").click(function(){ 
			timetableGrid[0].clearToolbar();
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




<h2>Timetable</h2>


<div class="buttonsDiv"></div>

<div id="dialogSelect"></div>

<div>
	<table id="timetable"></table>
	<div id="pgTimetable"></div>
	<div id="filterToolbar">Search</div>
</div>

<div class="buttonsDiv">
    <input type="button" class="ui-button" value="Add Record" id="addRow" />
    <input type="button" class="ui-button" value="Edit Record" id="editRow" />
    <input type="button" class="ui-button" value="Delete Record" id="delRow"/>    
    <input type="button" class="ui-button" value="Toggle Search Panel" id="toggleSearchPanel"/>    
    <input type="button" class="ui-button" value="Clear Search" id="clearSearchPanel"/>        
</div>



</body>
</html>